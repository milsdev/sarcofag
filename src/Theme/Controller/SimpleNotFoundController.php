<?php
namespace Sarcofag\Theme\Controller;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class SimpleNotFoundController extends SimpleRendererController
{
    /**
     * @param Request $request
     * @param Response $response
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response)
    {
        return $this->renderer
                    ->response($response, $this->templateToRender)
                    ->withStatus(404);
    }
}
