<?php
use Interop\Container\ContainerInterface;
use DI\Factory\RequestedEntry;

return [
    'ActionListener' => DI\object('Sarcofag\Service\SPI\EventManager\GenericListener'),
    'DataFilterListener' => DI\object('Sarcofag\Service\SPI\EventManager\GenericListener'),
    'DefaultStaticPostController' => DI\object('Sarcofag\Theme\Controller\StaticPostController'),
    'DefaultStaticPageController' => DI\object('Sarcofag\Theme\Controller\StaticPostController')
];
