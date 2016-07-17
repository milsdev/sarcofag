<?php
use Interop\Container\ContainerInterface;
use DI\Factory\RequestedEntry;

return [
    'EventManager' =>
                DI\get(Sarcofag\Service\SPI\EventManager\EventManager::class)
                   ->method('attachListeners', DI\get(\Sarcofag\Service\SPI\Widget\Registry::class))
                   ->method('attachListeners', DI\get(\Sarcofag\Service\SPI\Sidebar\Registry::class))
                   ->method('attachListeners', DI\get(\Sarcofag\Service\API\WP\WidgetFactory::class))

                  // Custom fields listeners
                   ->method('attachListeners', DI\get(\Sarcofag\Admin\CustomFields\ControllerPageMappingField::class))
];
