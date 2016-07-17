<?php
namespace Sarcofag\Service\SPI\EventManager;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\Service\SPI\EventManager\Action\ActionInterface;
use Sarcofag\Service\SPI\EventManager\DataFilter\DataFilterInterface;

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
