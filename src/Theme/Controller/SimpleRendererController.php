<?php
namespace Sarcofag\Theme\Controller;

use DI\FactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Sarcofag\View\Renderer\PsrHttpRendererInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class SimpleRendererController
{
    /**
     * @var PsrHttpRendererInterface
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $templateToRender;

    /**
     * StaticPostController constructor.
     *
     * @param PsrHttpRendererInterface $renderer
     * @param string $templateToRender
     */
    public function __construct(PsrHttpRendererInterface $renderer, $templateToRender)
    {
        $this->renderer = $renderer;
        $this->templateToRender = $templateToRender;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response)
    {
        return $this->renderer
                    ->response($response, $this->templateToRender);
    }
}
