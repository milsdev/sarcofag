<?php
namespace Sarcofag\Service\SPI\Action;

interface ActionInterface
{
    /**
     * @return EventInterface[]
     */
    public function getActionHandlers();
}
