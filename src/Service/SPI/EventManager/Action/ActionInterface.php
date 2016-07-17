<?php
namespace Sarcofag\Service\SPI\EventManager\Action;

use Sarcofag\Service\SPI\EventManager\ListenerInterface;

interface ActionInterface
{
    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners();
}
