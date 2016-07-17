<?php
namespace Sarcofag\Service\SPI\EventManager;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\Service\API\WP;
use Sarcofag\Service\SPI\EventManager\Action\ActionInterface;
use Sarcofag\Service\SPI\EventManager\DataFilter\DataFilterInterface;

class EventManager implements EventManagerInterface
{
    /**
     * @var WP
     */
    protected $wpService;

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
     * @param ActionInterface | DataFilterInterface $listenersAggregate
     * @throws RuntimeException
     */
    public function attachListeners($listenersAggregate)
    {
        if ($listenersAggregate instanceOf ActionInterface) {
            $this->register($listenersAggregate->getActionListeners(), WP::EVENT_TYPE_ACTION);
        } else if ($listenersAggregate instanceOf DataFilterInterface) {
            $this->register($listenersAggregate->getDataFilterListeners(), WP::EVENT_TYPE_FILTER);
        } else {
            throw new RuntimeException('Incorect handler passed to register, 
                                            action must implement Filter or Actor Interface');
        }
    }

    /**
     * @param ListenerInterface[] $listeners
     * @param string $type one of filter or action types
     */
    protected function register(array $listeners, $type)
    {
        if (!in_array($type, [WP::EVENT_TYPE_FILTER, WP::EVENT_TYPE_ACTION])) {
            throw new RuntimeException
                            ('Unsupported type of the event ['.$type.'], now supports only filter or action types');
        }

        foreach ($listeners as $listener) {
            foreach ($listener->getNames() as $name) {
                /**
                 * @see WP::add_action
                 * @see WP::add_filter
                 */
                $this->wpService->__call('add_'.strtolower($type),
                                            [$name,
                                             $listener->getCallable(),
                                             $listener->getPriority(),
                                             $listener->getArgc()]);
            }
        }
    }
}
