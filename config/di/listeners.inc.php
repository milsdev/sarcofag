<?php
use Interop\Container\ContainerInterface;

return [
    'EventManager' =>
                DI\object(\Sarcofag\SPI\EventManager\EventManager::class)
                   ->method('attachListeners', DI\get('SarcofagApp'))

                   ->method('attachListeners', DI\get('MenuRegistry'))
                   ->method('attachListeners', DI\get('WidgetRegistry'))
                   ->method('attachListeners', DI\get('SidebarRegistry'))
                   ->method('attachListeners', DI\get(\Sarcofag\API\WP\WidgetFactory::class))

                  // Custom fields listeners
                   ->method('attachListeners',
                                DI\get(\Sarcofag\Admin\CustomFields\ControllerPageMappingField::class))
];
