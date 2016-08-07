<?php
namespace Sarcofag\SPI\EventManager\DataFilter;

use Sarcofag\SPI\EventManager\ListenerInterface;

interface DataFilterInterface
{
    /**
     * @return ListenerInterface[] | array
     */
    public function getDataFilterListeners();
}
