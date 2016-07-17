<?php
namespace Sarcofag\Service\SPI\EventManager\DataFilter;

use Sarcofag\Service\SPI\EventManager\ListenerInterface;

interface DataFilterInterface
{
    /**
     * @return ListenerInterface[]
     */
    public function getDataFilterListeners();
}
