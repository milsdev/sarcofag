<?php
return [
    \Sarcofag\View\Renderer\RendererInterface::class =>
        DI\get('Renderer'),

    \Sarcofag\View\Renderer\PsrHttpRendererInterface::class =>
        DI\get('Renderer'),

    \Sarcofag\View\Helper\HelperManagerInterface::class =>
        DI\get('HelperManager'),

    \Sarcofag\SPI\RegisterActionInterface::class =>
        DI\get('EventsRegistrator'),

    \Sarcofag\SPI\RegisterFilterInterface::class =>
        DI\get('EventsRegistrator'),

    \Sarcofag\SPI\EventManager\EventManagerInterface::class =>
        DI\get('EventManager')
];
