<?php
namespace Sarcofag\Service\SPI\Filter;

interface FilterInterface
{
    /**
     * @return EventInterface[]
     */
    public function getFilterHandlers();
}
