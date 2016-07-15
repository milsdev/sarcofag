<?php
use Interop\Container\ContainerInterface;
use DI\Factory\RequestedEntry;

return [
    'ActionEvent' => DI\object('Sarcofag\Service\SPI\Action\ActionEvent'),
    'FilterEvent' => DI\object('Sarcofag\Service\SPI\Filter\FilterEvent')
];
