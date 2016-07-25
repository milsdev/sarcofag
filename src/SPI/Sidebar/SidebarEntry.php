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
     * SidebarEntry constructor.
     *
     * @param string $id
     * @param string $name
     * @param string $description
     * @param string $beforeTitle
     * @param string $afterTitle
     * @param string $beforeWidget
     * @param string $afterWidget
     */
    public function __construct($id, $name, $description = "",
                                $beforeTitle = "",
                                $afterTitle = "",
                                $beforeWidget = "",
                                $afterWidget = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->beforeTitle = $beforeTitle;
        $this->afterTitle = $afterTitle;
        $this->beforeWidget = $beforeWidget;
        $this->afterWidget = $afterWidget;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getBeforeTitle()
    {
        return $this->beforeTitle;
    }

    /**
     * @param string $beforeTitle
     */
    public function setBeforeTitle($beforeTitle)
    {
        $this->beforeTitle = $beforeTitle;
    }

    /**
     * @return string
     */
    public function getAfterTitle()
    {
        return $this->afterTitle;
    }

    /**
     * @param string $afterTitle
     */
    public function setAfterTitle($afterTitle)
    {
        $this->afterTitle = $afterTitle;
    }

    /**
     * @return string
     */
    public function getBeforeWidget()
    {
        return $this->beforeWidget;
    }

    /**
     * @param string $beforeWidget
     */
    public function setBeforeWidget($beforeWidget)
    {
        $this->beforeWidget = $beforeWidget;
    }

    /**
     * @return string
     */
    public function getAfterWidget()
    {
        return $this->afterWidget;
    }

    /**
     * @param string $afterWidget
     */
    public function setAfterWidget($afterWidget)
    {
        $this->afterWidget = $afterWidget;
    }
}
