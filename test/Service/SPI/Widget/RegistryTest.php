<?php

namespace SarcofagTest\Service\SPI\Widget;

use DI\FactoryInterface;
use Sarcofag\API\WP\WidgetFactoryInterface;
use Sarcofag\API\WP\WidgetInterface;
use Sarcofag\SPI\EventManager\ListenerInterface;

class RegistryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var WidgetFactoryInterface
     */
    protected $widgetFactory;

    protected $attached = [];

    public function setUp()
    {
        $this->factory = $this->getMockForAbstractClass(FactoryInterface::class);
        $this->widgetFactory = $this->getMockBuilder(WidgetFactoryInterface::class)->getMock();
    }

    /**
     * @param $widgetClassNameOrAlias
     *
     * @dataProvider attachProvider
     *
     * @return $this
     */
    public function testAttach($widgetClassNameOrAlias)
    {
        $array = ['widgetClassNameOrAlias' => $widgetClassNameOrAlias];
        $this->factory
            ->expects($this->once())
            ->method('make')
            ->with($this->equalTo('Sarcofag\API\WP\Widget'), $array)
            ->willReturn($this->getMockForAbstractClass(WidgetInterface::class));

        $this->assertArraySubset(['widgetClassNameOrAlias' => 'testClass1'], $array);

        $wrapped = $this->factory->make('Sarcofag\API\WP\Widget', $array);

        $this->assertInstanceOf(WidgetInterface::class, $wrapped);

        $this->attached[] = $wrapped;

        $this->assertContains($wrapped, $this->attached);
    }

    public function attachProvider()
    {
        return [
            ['testClass1'],
        ];
    }

    public function testGetActionListeners()
    {
        $widgetsInit = function () {
            foreach ($this->attached as $k=>$attachedItem) {
                $this->widgetFactory->register($attachedItem, $attachedItem->getId());
            }
        };

        $array = ['names' => 'widgets_init', 'callable' => $widgetsInit];

        $this->factory
            ->expects($this->once())
            ->method('make')
            ->with($this->equalTo('ActionListener'), $array)
            ->willReturn($this->getMockForAbstractClass(ListenerInterface::class));

        $result = $this->factory->make('ActionListener', $array);

        $this->assertInstanceOf(ListenerInterface::class, $result);
    }

}