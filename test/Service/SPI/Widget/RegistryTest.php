<?php

namespace SarcofagTest\Service\SPI\Widget;

use DI\FactoryInterface;
use Sarcofag\API\WP\WidgetFactory;

class RegistryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var WidgetFactory
     */
    protected $widgetFactory;

    protected $attached = [];

    public function setUp()
    {
        $this->factory = $this->getMockForAbstractClass(FactoryInterface::class);
//        $this->widgetFactory = $this->getMockBuilder(WidgetFactory::class)->getMock();
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
        $this->assertStringMatchesFormat('%s', $widgetClassNameOrAlias);
        $wrapped = $this->factory->make('Sarcofag\API\WP\Widget',
                                        ['widgetClassNameOrAlias' => $widgetClassNameOrAlias]);
        $this->attached[] = $wrapped;
        return $this;
    }

    public function attachProvider()
    {
        return [
            ['testClass1'],
            ['testClass2'],
        ];
    }

}