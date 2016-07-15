<?php
use Interop\Container\ContainerInterface;
use DI\Factory\RequestedEntry;

return [
    'EventsRegistrator' =>
                DI\object(Sarcofag\Service\SPI\EventsRegistrator::class)
                   ->method('attach', DI\get(\Sarcofag\Service\SPI\Widget\Registry::class))
                   ->method('attach', DI\get(\Sarcofag\Service\SPI\Sidebar\Registry::class))
                   ->method('attach', DI\get(\Sarcofag\Service\API\WP\WidgetFactory::class))
];
