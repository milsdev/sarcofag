<?php
use Interop\Container\ContainerInterface;
use DI\Factory\RequestedEntry;

return [
    'ActionListener' => DI\object(Sarcofag\Service\SPI\EventManager\GenericListener::class),
    'DataFilterListener' => DI\object(Sarcofag\Service\SPI\EventManager\GenericListener::class),
    'DefaultStaticPostController' => DI\object(Sarcofag\Theme\Controller\StaticPostController::class),
    'DefaultStaticPageController' => DI\object(Sarcofag\Theme\Controller\StaticPostController::class),
    'GenericMenu' => DI\object(Sarcofag\Service\SPI\Menu\Menu::class),
    'MenuRegistry' => DI\object(Sarcofag\Service\SPI\Menu\Registry::class),
    'SidebarRegistry' => DI\object(Sarcofag\Service\SPI\Sidebar\Registry::class),
    'WidgetRegistry' => DI\object(Sarcofag\Service\SPI\Widget\Registry::class)
];
