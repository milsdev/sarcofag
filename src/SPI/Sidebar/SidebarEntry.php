<?php
namespace Sarcofag\SPI\Sidebar;

class SidebarEntry implements SidebarEntryInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description = "";

    /**
     * @var string
     */
    protected $beforeTitle = "";

    /**
     * @var string
     */
    protected $afterTitle = "";

    /**
     * @var string
     */
    protected $beforeWidget = "";

    /**
     * @var string
     */
    protected $afterWidget = "";

    /**
     * @var array
     */
    protected $customFields = [];

    /**
     * SidebarEntry constructor.
     *
     * @param string $id
     * @param string $name
     * @param string $description
     * @param string $beforeTitle
     * @param string $afterTitle
     * @param string $beforeWidget
     * @param string $afterWidget
     * @param array $customFields
     */
    public function __construct($id, $name, $description = "",
                                $beforeTitle = "",
                                $afterTitle = "",
                                $beforeWidget = "",
                                $afterWidget = "",
                                $customFields = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->beforeTitle = $beforeTitle;
        $this->afterTitle = $afterTitle;
        $this->beforeWidget = $beforeWidget;
        $this->afterWidget = $afterWidget;
        $this->customFields = $customFields;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getBeforeTitle()
    {
        return $this->beforeTitle;
    }

    /**
     * @return string
     */
    public function getAfterTitle()
    {
        return $this->afterTitle;
    }

    /**
     * @return string
     */
    public function getBeforeWidget()
    {
        return $this->beforeWidget;
    }

    /**
     * @return string
     */
    public function getAfterWidget()
    {
        return $this->afterWidget;
    }

    /**
     * @return array
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }
}
