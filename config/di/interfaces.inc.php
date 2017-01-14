<?php
return [
    \Sarcofag\View\Renderer\RendererInterface::class =>
        DI\get('Renderer'),

    \Sarcofag\View\Renderer\PsrHttpRendererInterface::class =>
        DI\get('Renderer'),

    \Sarcofag\View\Helper\HelperManagerInterface::class =>
        DI\get('HelperManager'),

    \Sarcofag\SPI\EventManager\EventManagerInterface::class =>
        DI\get('EventManager'),

    \Sarcofag\SPI\Routing\PostFilterInterface::class =>
        DI\get(\Sarcofag\SPI\Routing\PostFilter::class)
];
