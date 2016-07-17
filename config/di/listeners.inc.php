<?php
use Interop\Container\ContainerInterface;

return [
    'EventManager' =>
                DI\object(\Sarcofag\Service\SPI\EventManager\EventManager::class)
                   ->method('attachListeners', DI\get(\Sarcofag\App::class))

                   ->method('attachListeners', DI\get('MenuRegistry'))
                   ->method('attachListeners', DI\get('WidgetRegistry'))
                   ->method('attachListeners', DI\get('SidebarRegistry'))
                   ->method('attachListeners', DI\get(\Sarcofag\Service\API\WP\WidgetFactory::class))

                  // Custom fields listeners
                   ->method('attachListeners', DI\get(\Sarcofag\Admin\CustomFields\ControllerPageMappingField::class))
];
