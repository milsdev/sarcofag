<?php
namespace Sarcofag\SPI\EventManager;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\EventManager\DataFilter\DataFilterInterface;

interface EventManagerInterface
{
    /**
     * Facade method to detect functionality of the
     * action and pass it to correct attacher.
     *
     * @param ActionInterface | DataFilterInterface $listenersAggregate
     * @throws RuntimeException
     */
    public function attachListeners($listenersAggregate);
}
