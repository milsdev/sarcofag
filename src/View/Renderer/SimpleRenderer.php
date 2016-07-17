<?php
namespace Sarcofag\View\Renderer;

use Sarcofag\Service\API\WP;
use Sarcofag\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;
use Sarcofag\View\Helper\HelperManagerInterface;

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
     * SimpleRenderer constructor.
     *
     * @param HelperManager $helperManager
     * @param array $templaterConfig
     * @param WP $wp
     */
    public function __construct(HelperManagerInterface $helperManager,
                                array $templaterConfig,
                                WP $wp)
    {
        $this->templateConfig = $templaterConfig;
        $this->helperManager = $helperManager;
        $this->wp = $wp;
    }

    /**
     * @param $template
     *
     * @return string
     */
    protected function getTemplatePath($template)
    {
        $parts = explode('/', $template);
        if (count($parts) < 1) {
            throw new RuntimeException('Template path ['.$template.'] has incorrect format, '.
                                        'it is must start from theme-name or alias path');
        }
        
        $extracted = array_splice($parts, 0, 1);

        $alias = $extracted[0];
        if (!array_key_exists($alias, $this->templateConfig)) {
            $alias = $this->wp->wp_get_theme()->template;
            array_unshift($parts, $extracted[0]);
        }
        

        $fullTemplatePath = rtrim($this->templateConfig[$alias], '/') . '/' . join('/', $parts);
        
        if (!file_exists($fullTemplatePath)) {
            throw new RuntimeException('Could not found template ['.$fullTemplatePath.'] '.
                                            'please check requested template name');
        }
        
        return $fullTemplatePath;
    }

    /**
     * @param string $templatePath
     * @param string $alias [OPTIONAL]
     */
    public function setThemeTemplatePath($templatePath, $alias = '')
    {
        if (empty($alias)) {
            $alias = $this->wp->wp_get_theme()->template;
        }

        $this->templateConfig[$alias] = $templatePath;
    }

    /**
     * Render a template
     *
     * $data cannot contain template as a key
     *
     * throws RuntimeException if $templatePath . $template does not exist
     *
     * @param                    $template
     * @param array              $data
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function render($template, array $data = [])
    {
        $render = \Closure::bind(function ($template, $data) {
            extract($data);
            include $template;
        }, $this->helperManager, get_class($this->helperManager));

        ob_start();
        $render($this->getTemplatePath($template), $data);
        return ob_get_clean();
    }

    /**
     * Response with template
     *
     * $data cannot contain template as a key
     *
     * throws RuntimeException if $templatePath . $template does not exist
     *
     * @param ResponseInterface $response
     * @param                    $template
     * @param array              $data
     *
     * @return ResponseInterface
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function response(ResponseInterface $response, $template, array $data = [])
    {
        $output = $this->render($template, $data);
        $response->getBody()->write($output);

        return $response;
    }
}
