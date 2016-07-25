<?php
namespace Sarcofag\SPI\EventManager\DataFilter;

use Sarcofag\SPI\EventManager\ListenerInterface;

interface DataFilterInterface
{
    /**
     * @return ListenerInterface[]
     */
    public function getDataFilterListeners();
}
