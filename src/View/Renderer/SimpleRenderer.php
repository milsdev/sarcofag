<?php
/**
 * Sarcofag (http://sarcofag.com)
 *
 * @link       https://github.com/milsdev/sarcofag
 * @copyright  Copyright (c) 20012-2016 Mil's (http://www.mils.agency)
 * @license    http://sarcofag.com/license/mit
 */

namespace Sarcofag\View\Renderer;

use DI\FactoryInterface;
use Sarcofag\API\WP;
use Sarcofag\Utility\Closure;
use Sarcofag\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;
use Sarcofag\View\Helper\HelperManagerInterface;

/**
 * Simple Renderer.
 *
 * This renderer responsible for the rendering phtml
 * views, in context with Helpers and local scope,
 * to avoid global scope conflicts.
 */
class SimpleRenderer implements RendererInterface, PsrHttpRendererInterface
{

    /**
     * @var HelperManagerInterface
     */
    protected $helperManager;

    /**
     * @var array
     */
    protected $templateConfig;

    /**
     * @var WP
     */
    protected $wp;

    /**
     * @var FactoryInterface
     */
    protected $factory;


    /**
     * SimpleRenderer constructor.
     *
     * @param HelperManagerInterface $helperManager   Container with all Helpers.
     * @param array                  $templaterConfig Array with the pathes to the template/view dirs.
     * @param FactoryInterface       $factory         Factory service to create Closure instance.
     * @param WP                     $wp              Wordpress API object, to be able to call wordpress specific
     *                                                functions.
     */
    public function __construct(
        HelperManagerInterface $helperManager,
        array $templaterConfig,
        FactoryInterface $factory,
        WP $wp
    ) {
        $this->templateConfig = $templaterConfig;
        $this->helperManager  = $helperManager;
        $this->factory        = $factory;
        $this->wp             = $wp;
    }


    /**
     * @param string $template Relative path to the template.
     *
     * @return string
     * @throws RuntimeException Throws if template has incorrect format or template could not be found.
     */
    protected function getTemplatePath($template)
    {
        $parts = explode('/', $template);
        if (count($parts) < 1) {
            throw new RuntimeException(
                "Template path [{$template}] has incorrect format, it is must start from theme-name or alias path"
            );
        }

        $extracted = array_splice($parts, 0, 1);

        $alias = $extracted[0];
        if (!array_key_exists($alias, $this->templateConfig)) {
            $alias = $this->wp->wp_get_theme()->template;
            array_unshift($parts, $extracted[0]);
        }

        $fullTemplatePath = rtrim($this->templateConfig[$alias], '/').'/'.join('/', $parts);

        if (!file_exists($fullTemplatePath)) {
            throw new RuntimeException(
                "Could not found template [{$fullTemplatePath}] please check requested template name"
            );
        }

        return $fullTemplatePath;
    }

    /**
     * Render a template
     *
     * $data cannot contain template as a key
     *
     * throws RuntimeException if $templatePath . $template does not exist
     *
     * @param string $template Relative path to the template to render in current context.
     * @param array  $data     Variables and different data which must be included into rendering context.
     *
     * @return string
     * @throws RuntimeException Throws if template has incorrect format or template could not be found.
     */
    public function render($template, array $data = [])
    {
        $closure = $this->factory->make(Closure::class, ['closure'=>function ($template, $data) {
            extract($data);
            include $template;
        }]);
        $render = $closure->bindTo(
            $this->helperManager,
            get_class($this->helperManager)
        );

        ob_start();
        $render($this->getTemplatePath($template), $data);
        return ob_get_clean();
    }


    /**
     * Response with template
     *
     * $data cannot contain template as a key
     *
     * throws RuntimeException if $templatePath.$template does not exist
     *
     * @param ResponseInterface $response Response object to contain rendered template output.
     * @param string            $template Template path to the requesting template.
     * @param array             $data     Variables and different data to include into the rendering context.
     *
     * @return ResponseInterface
     * @throws RuntimeException Throws if template has incorrect format or template could not be found.
     */
    public function response(ResponseInterface $response, $template, array $data = [])
    {
        $output = $this->render($template, $data);
        $response->getBody()->write($output);

        return $response;
    }
}
