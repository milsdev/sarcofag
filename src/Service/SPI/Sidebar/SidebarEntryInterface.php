<?php
namespace Sarcofag\Service\SPI\Sidebar;

interface SidebarEntryInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getBeforeTitle();

    /**
     * @return string
     */
    public function getAfterTitle();

    /**
     * @return string
     */
    public function getBeforeWidget();

    /**
     * @return string
     */
    public function getAfterWidget();
}
