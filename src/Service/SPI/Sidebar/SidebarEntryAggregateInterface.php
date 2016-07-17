<?php
namespace Sarcofag\Service\SPI\Sidebar;

interface SidebarEntryAggregateInterface
{
    /**
     * @return SidebarEntryInterface[]
     */
    public function getSidebarEntries();
}
