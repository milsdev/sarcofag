<?php
namespace Sarcofag\View\Renderer;

use Psr\Http\Message\ResponseInterface;

interface PsrHttpRendererInterface
{
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
    public function response(ResponseInterface $response, $template, array $data = []);
}
