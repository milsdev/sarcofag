<?php
use Interop\Container\ContainerInterface;
use DI\Factory\RequestedEntry;

return [
    'ActionListener' => DI\object(Sarcofag\SPI\EventManager\GenericListener::class),
    'DataFilterListener' => DI\object(Sarcofag\SPI\EventManager\GenericListener::class),
    'DefaultStaticPostController' => DI\object(Sarcofag\Theme\Controller\StaticPostController::class),
    'DefaultStaticPageController' => DI\object(Sarcofag\Theme\Controller\StaticPostController::class),
    'Renderer' => DI\object(Sarcofag\View\Renderer\SimpleRenderer::class),
    'GenericMenu' => DI\object(Sarcofag\SPI\Menu\Menu::class),
    'GenericSidebar' => DI\object(Sarcofag\SPI\Sidebar\SidebarEntry::class),
    'MenuRegistry' => DI\object(Sarcofag\SPI\Menu\Registry::class),
    'SidebarRegistry' => DI\object(Sarcofag\SPI\Sidebar\Registry::class),
    'WidgetRegistry' => DI\object(Sarcofag\SPI\Widget\Registry::class)
];
