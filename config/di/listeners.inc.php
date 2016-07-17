<?php
use Interop\Container\ContainerInterface;

return [
    'EventManager' =>
                DI\object(\Sarcofag\Service\SPI\EventManager\EventManager::class)
                   ->method('attachListeners', DI\get(\Sarcofag\App::class))

                   ->method('attachListeners', DI\get(\Sarcofag\Service\SPI\Widget\Registry::class))
                   ->method('attachListeners', DI\get(\Sarcofag\Service\SPI\Sidebar\Registry::class))
                   ->method('attachListeners', DI\get(\Sarcofag\Service\API\WP\WidgetFactory::class))

                  // Custom fields listeners
                   ->method('attachListeners', DI\get(\Sarcofag\Admin\CustomFields\ControllerPageMappingField::class))
];
