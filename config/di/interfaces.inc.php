<?php
return [
    \Sarcofag\View\Renderer\RendererInterface::class =>
        DI\get(\Sarcofag\View\Renderer\SimpleRenderer::class),

    \Sarcofag\View\Renderer\PsrHttpRendererInterface::class =>
        DI\get(\Sarcofag\View\Renderer\SimpleRenderer::class),
];
