<?php

namespace SarcofagTest\Service\SPI\Widget;

use Sarcofag\API\ACF\ACFField;
use Sarcofag\API\WP;
use Sarcofag\API\WP\Widget as WPWidget;
use Sarcofag\Filter\WidgetIdFilter;

class ACFWidgetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ACFField
     */
    protected $acfField;

    protected $wpWidget;

    public function setUp()
    {
        $wpService = $this->getMock(WP::class);
        $widgetIdFilter = $this->getMock(WidgetIdFilter::class);

        $this->acfField = $this->getMockBuilder(ACFField::class)
                               ->setConstructorArgs(['wpService' => $wpService, 'widgetIdFilter' => $widgetIdFilter])
                               ->getMock();

        $this->acfField->method('getWidgetField')->willReturn('String');

//        $this->wpWidget = $this->getMockBuilder(WPWidget::class)->getMock();
    }

    /**
     * @param String $name
     *
     * @dataProvider nameProvider
     *
     * @return mixed
     */
    public function testGetField($name)
    {
        $this->assertStringMatchesFormat('%s', $name);
        $this->assertStringMatchesFormat('%s', $this->acfField->getWidgetField($name, 'mils_header_sidebar'));
    }

    public function nameProvider()
    {
        return [
            ['title'],
            ['description']
        ];
    }

    /**
     * @param array $placeholderParams
     * @param array $settings
     *
     * @dataProvider rendererProvider
     */
    public function testRender(array $placeholderParams = [], array $settings)
    {
//        return $this->renderer->render($this->params->getThemeTemplate(),
//            $placeholderParams +
//            ['wpWidget' => $this->wpWidget,
//                'settings' => $settings,
//                'getField' => function ($name) {
//                    return $this->getField($name);
//                }
//            ]);
    }

    public function rendererProvider()
    {
        return [
            [[], []]
        ];
    }
}