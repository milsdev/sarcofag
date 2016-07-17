<?php
use Interop\Container\ContainerInterface;
use DI\Factory\RequestedEntry;

return [
    'ActionListener' => DI\object('Sarcofag\Service\SPI\EventManager\GenericListener'),
    'DataFilterListener' => DI\object('Sarcofag\Service\SPI\EventManager\GenericListener')
];
