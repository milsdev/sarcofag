<?php
namespace Sarcofag\Service\SPI\Widget;

use DI\FactoryInterface;
use Sarcofag\Service\API\WP\WidgetFactory;
use Sarcofag\Service\SPI\Action\ActionInterface;
use Sarcofag\Service\SPI\Action\EventInterface;

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
        
        $this->attached[] = $this->factory->make('Sarcofag\Service\API\WP\Widget',
                                                      ['widgetId' => spl_object_hash($widget),
                                                       'widgetName' => $widget->getName(),
                                                       'widgetOptions' => $widgetOptions,
                                                       'widget' => $widget]);

        return $this;
    }


    /**
     * @return EventInterface[]
     */
    public function getActionHandlers()
    {
        $widgetsInit = function () {
            foreach ($this->attached as $attachedItem) {
                $this->widgetFactory->register($attachedItem);
            }
        };

        return [$this->factory->make('ActionEvent', ['name' => 'widgets_init', 'callable' => $widgetsInit])];
    }

}
