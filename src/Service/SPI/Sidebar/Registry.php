<?php
namespace Sarcofag\Service\SPI\Sidebar;

use DI\FactoryInterface;
use Sarcofag\Service\API\WP;
use Sarcofag\Service\SPI\Action\ActionInterface;
use Sarcofag\Service\SPI\Sidebar\SidebarEntryInterface;

class Registry implements ActionInterface
{
    /**
     * @var WP
     */
    protected $wpService;

    /**
     * @var SidebarEntryInterface[]
     */
    protected $attached = [];

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * Registry constructor.
     *
     * @param WP $wpService
     * @param FactoryInterface $factory
     */
    public function __construct(WP $wpService, FactoryInterface $factory)
    {
        $this->wpService = $wpService;
        $this->factory = $factory;
    }

    /**
     * @param SidebarEntryInterface $sidebarEntry
     *
     * @return $this
     */
    public function attach(SidebarEntryInterface $sidebarEntry)
    {

        $this->attached[] = $sidebarEntry;
        return $this;
    }

    /**
     * @return EventInterface[]
     */
    public function getActionHandlers()
    {
        $sidebarsInit = function () {
            foreach ($this->attached as $attachedItem) {
                $args = array(
                    'id'            => $attachedItem->getId(),
                    'name'          => $attachedItem->getName(),
                    'description'   => $attachedItem->getDescription(),
                    'before_title'  => $attachedItem->getBeforeTitle(),
                    'after_title'   => $attachedItem->getAfterTitle(),
                    'before_widget' => $attachedItem->getBeforeWidget(),
                    'after_widget'  => $attachedItem->getAfterWidget(),
                );
    
                $this->wpService->register_sidebar($args);
            }
        };
    
        return [$this->factory->make('ActionEvent', ['name' => 'widgets_init', 'callable' => $sidebarsInit])];
    }
}
