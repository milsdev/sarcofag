<?php
namespace Sarcofag\Service\SPI\Widget;

use DI\FactoryInterface;
use Sarcofag\Service\API\WP\WidgetFactory;
use Sarcofag\Service\SPI\EventManager\Action\ActionInterface;
use Sarcofag\Service\SPI\EventManager\ListenerInterface;

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
     * @var \Sarcofag\Service\API\WP\Widget[]
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
     * @param WidgetInterface $widget
     * @param array $widgetOptions
     *
     * @return $this
     */
    public function attach(WidgetInterface $widget, array $widgetOptions = [])
    {
        $wrapped = $this->factory->make('Sarcofag\Service\API\WP\Widget',
                                        ['widgetId' => $widget->getId(),
                                         'widgetName' => $widget->getName(),
                                         'widgetOptions' => $widgetOptions,
                                         'widget' => $widget]);

        if ($widget instanceof GenericWidget) {
            $this->attached[md5($widget->getName())] = $wrapped;
        } else {
            $this->attached[] = $wrapped;
        }

        return $this;
    }


    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners()
    {
        $widgetsInit = function () {
            foreach ($this->attached as $k=>$attachedItem) {
                if (is_numeric($k)) {
                    $this->widgetFactory->register($attachedItem);
                } else {
                    $this->widgetFactory->register($attachedItem, $k);
                }
            }
        };

        return [$this->factory->make('ActionListener', ['names' => 'widgets_init', 'callable' => $widgetsInit])];
    }

}
