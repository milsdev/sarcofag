<?php
namespace Sarcofag\SPI\EventManager\Action;

use Sarcofag\SPI\EventManager\ListenerInterface;

interface ActionInterface
{
    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners();
}
