<?php
namespace Sarcofag\Service\SPI\Menu;

use DI\FactoryInterface;
use Sarcofag\Service\API\WP;
use Sarcofag\Service\SPI\EventManager\Action\ActionInterface;
use Sarcofag\Service\SPI\EventManager\ListenerInterface;

class Registry implements ActionInterface
{
    /**
     * @var WP
     */
    protected $wpService;

    /**
     * @var MenuInterface[]
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
     * @param MenuInterface $menu
     *
     * @return $this
     */
    public function attach(MenuInterface $menu)
    {

        $this->attached[] = $menu;
        return $this;
    }

    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners()
    {
        $menusInit = function () {
            foreach ($this->attached as $attachedItem) {
                $this->wpService->register_nav_menu($attachedItem->getId(), $attachedItem->getDescription());
            }
        };
    
        return [$this->factory->make('ActionListener', ['names' => 'after_setup_theme', 'callable' => $menusInit])];
    }
}
