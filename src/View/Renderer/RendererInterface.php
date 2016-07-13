<?php
namespace Sarcofag\View\Renderer;

use Psr\Http\Message\ResponseInterface;

interface RendererInterface
{
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
    public function render($template, array $data = []);
}
