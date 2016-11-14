<?php
namespace Sarcofag\SPI\EventManager;

use DI\FactoryInterface;
use Sarcofag\API\WP;
use Sarcofag\SPI\EventManager\Handler\GenericAjaxHandler;
use Sarcofag\SPI\EventManager\Handler\HandlerInterface;

class ListenerFactory
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * ListenerFactory constructor.
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param string $type
     * @param array | ListenerInterface $listener
     *
     * @return mixed
     */
    public function makeListener($type, $listener)
    {
        if (!in_array($type, [WP::EVENT_TYPE_FILTER, WP::EVENT_TYPE_ACTION])) {
            throw new RuntimeException
                        ('Unsupported type of the event [' . $type . '], now supports only filter or action types');
        }

        $listenerHandler = '';
        if (is_array($listener) && $type == WP::EVENT_TYPE_ACTION) {
            if (array_key_exists('ajax', $listener) && $listener['ajax'] === true) {
                if (array_key_exists('callable', $listener) &&
                        !($listener['callable'] instanceof HandlerInterface)) {
                    $listener['handler'] = $this->factory
                                                ->make(GenericAjaxHandler::class,
                                                        ['callable' => $listener['callable']]);
                }
                
                $listener = $this->factory->make('AjaxActionListener', $listener);
            } else {
                $listener = $this->factory->make('ActionListener', $listener);
            }
        } else if (is_array($listener) && $type == WP::EVENT_TYPE_FILTER) {
            $listener = $this->factory->make('DataFilterListener', $listener);
        }

        if (!$listener instanceof ListenerInterface) {
            throw new RuntimeException('Listener must be type of ListenerInterface');
        }

        return $listener;
    }
}
