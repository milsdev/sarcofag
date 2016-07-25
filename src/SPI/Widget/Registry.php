<?php
namespace Sarcofag\SPI\Widget;

use DI\FactoryInterface;
use Sarcofag\API\WP\WidgetFactory;
use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\EventManager\ListenerInterface;

class Registry implements ActionInterface
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var WidgetFactory
     */
    protected $widgetFactory;

    /**
     * @var \Sarcofag\API\WP\Widget[]
     */
    protected $attached = [];

    /**
     * Registry constructor.
     *
     * @param FactoryInterface $factory
     * @param WidgetFactory $widgetFactory
     */
    public function __construct(FactoryInterface $factory,
                                WidgetFactory $widgetFactory)
    {
        $this->factory = $factory;
        $this->widgetFactory = $widgetFactory;
    }

    /**
     * @param string $widgetClassNameOrAlias
     *
     * @return $this
     */
    public function attach($widgetClassNameOrAlias)
    {
        $wrapped = $this->factory->make('Sarcofag\API\WP\Widget',
                                        ['widgetClassNameOrAlias' => $widgetClassNameOrAlias]);
        $this->attached[] = $wrapped;
        return $this;
    }


    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners()
    {
        $widgetsInit = function () {
            foreach ($this->attached as $k=>$attachedItem) {
                $this->widgetFactory->register($attachedItem, $attachedItem->getId());
            }
        };

        return [$this->factory->make('ActionListener', ['names' => 'widgets_init', 'callable' => $widgetsInit])];
    }

}
