<?php
namespace Sarcofag\SPI\Plugin;

use DI;
use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\EventManager\ListenerInterface;

abstract class PluginEntryAbstract 
    implements PluginEntryInterface, ActionInterface
{
    /**
     * @var DI\FactoryInterface
     */
    protected $factory;

    /**
     * App constructor.
     *
     * @param DI\FactoryInterface $factory
     */
    public function __construct(DI\FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return void
     */
    protected function init() {}

    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners()
    {
        return [
            $this->factory->make('ActionListener', [
                'names' => 'init',
                'callable' => function () { return $this->init(); },
                'priority' => 10
            ])
        ];
    }
}
