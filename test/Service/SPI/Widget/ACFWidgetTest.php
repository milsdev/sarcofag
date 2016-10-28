<?php

namespace SarcofagTest\Service\SPI\Widget;

use Sarcofag\API\WP;
use Sarcofag\API\ACF\ACFField;
use Sarcofag\API\WP\WidgetInterface as WpWidgetInterface;
use Sarcofag\Filter\WidgetIdFilter;
use Sarcofag\SPI\Widget\Params\RenderableInterface;
use Sarcofag\View\Renderer\RendererInterface;

class ACFWidgetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ACFField
     */
    protected $acfField;

    /**
     * @var WP\WidgetInterface
     */
    protected $wpWidget;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var RenderableInterface
     */
    protected $params;

    public function setUp()
    {
        $wpService = $this->getMock(WP::class);
        $widgetIdFilter = $this->getMock(WidgetIdFilter::class);

        $this->acfField = $this->getMockBuilder(ACFField::class)
                               ->setConstructorArgs(['wpService' => $wpService, 'widgetIdFilter' => $widgetIdFilter])
                               ->getMock();

        $this->acfField->method('getWidgetField')->willReturn('String');
        $this->wpWidget = $this->getMockForAbstractClass(WpWidgetInterface::class);

        /** @var RenderableInterface $widgetParams */
        $widgetParams = $this->getMockForAbstractClass(RenderableInterface::class);

        $widgetParams->method('getRenderer')->willReturn(
            $this->getMockForAbstractClass(RendererInterface::class)
        );

        $this->params = $widgetParams;
        $this->renderer = $widgetParams->getRenderer();

    }

    protected function getField($name)
    {
        return 'string';
    }

    /**
     * @param array $placeholderParams
     * @param array $settings
     */
    protected function renderAcfAwaredView(array $placeholderParams = [], array $settings)
    {
        $callable = function ($name) {
            return $this->getField($name);
        };

        $array = $placeholderParams + ['wpWidget' => $this->wpWidget, 'settings' => $settings, 'getField' => $callable];

        $this->params
            ->expects($this->once())
            ->method('getThemeTemplate')
            ->willReturn('string');

        $this->renderer
            ->expects($this->once())
            ->method('render')
            ->with($this->equalTo('string'), $array);

        $this->assertArraySubset(
            [
                'wpWidget' => $this->getMockForAbstractClass(WpWidgetInterface::class),
                'settings' => [],
                'getField' => $callable
            ], $array);

        $this->renderer->render($this->params->getThemeTemplate(), $placeholderParams + $array);
    }

    public function rendererProvider()
    {
        return [
            [[], []]
        ];
    }

    /**
     * @param array $placeholderParams
     * @param array $settings
     *
     * @dataProvider rendererProvider
     */
    public function testRender(array $placeholderParams, array $settings)
    {
        $this->renderAcfAwaredView($placeholderParams, $settings);
    }
}