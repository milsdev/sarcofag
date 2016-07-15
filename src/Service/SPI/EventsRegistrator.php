<?php
namespace Sarcofag\Service\SPI;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\Service\API\WP;
use Sarcofag\Service\SPI\Action\ActionInterface;
use Sarcofag\Service\SPI\Filter\FilterInterface;

class EventsRegistrator implements RegisterActionInterface, RegisterFilterInterface
{
    /**
     * @var WP
     */
    protected $wpService;

    /**
     * @var ActionInterface[]
     */
    protected $actions = [];

    /**
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * ActionRegistrationService constructor.
     *
     * @param WP $wpService
     */
    public function __construct(WP $wpService)
    {
        $this->wpService = $wpService;
    }

    /**
     * Facade method to detect functionality of the
     * action and pass it to correct attacher.
     *
     * @param ActionInterface|FilterInterface $action
     * @throws RuntimeException
     */
    public function attach($handler)
    {
        if ($handler instanceOf ActionInterface) {
            $this->attachAction($handler);
        } else if ($handler instanceOf FilterInterface) {
            $this->attachFilter($handler);
        } else {
            throw new RuntimeException('Incorect handler passed to register, 
                                            action must implement Filter or Actor Interface');
        }
    }

    /**
     * Register all events
     */
    public function register()
    {
        $this->registerActions();
        $this->registerFilters();
    }

    /**
     * @param ActionInterface $action
     */
    public function attachAction(ActionInterface $action)
    {
        $this->actions[] = $action;
    }

    /**
     * Register wordpress actions
     */
    public function registerActions()
    {
        foreach ($this->actions as $action) {
            foreach ($action->getActionHandlers() as $handler) {
                foreach ($handler->getNames() as $name) {
                    $this->wpService->add_action($name, $handler);
                }
            }
        }
    }

    /**
     * @param FilterInterface $filter
     */
    public function attachFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Register wordpress filters
     */
    public function registerFilters()
    {
        foreach ($this->filters as $filter) {
            foreach ($filter->getFilterHandlers() as $handler) {
                foreach ($handler->getNames() as $name) {
                    $this->wpService
                         ->add_filter($name, $handler,
                                      $handler->getPriority(),
                                      $handler->getArgs());
                }
            }
        }
    }
}
