<?php
namespace Sarcofag\SPI\Sidebar;

interface SidebarEntryAggregateInterface
{
    /**
     * @return SidebarEntryInterface[]
     */
    public function getSidebarEntries();
}
