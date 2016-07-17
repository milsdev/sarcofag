<?php
return [
    \Sarcofag\View\Renderer\RendererInterface::class =>
        DI\get(\Sarcofag\View\Renderer\SimpleRenderer::class),

    \Sarcofag\View\Renderer\PsrHttpRendererInterface::class =>
        DI\get(\Sarcofag\View\Renderer\SimpleRenderer::class),

    \Sarcofag\Service\SPI\RegisterActionInterface::class =>
        DI\get('EventsRegistrator'),

    \Sarcofag\Service\SPI\RegisterFilterInterface::class =>
        DI\get('EventsRegistrator'),

    \Sarcofag\Service\SPI\EventManager\EventManagerInterface::class =>
        DI\get('EventManager')
];
