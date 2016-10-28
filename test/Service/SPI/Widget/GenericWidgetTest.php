<?php

namespace SarcofagTest\Service\SPI\Widget;

use Sarcofag\API\WP\WidgetInterface;
use Sarcofag\SPI\Widget\Params\RenderableInterface;
use Sarcofag\View\Renderer\RendererInterface;

class GenericWidgetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RenderableInterface
     */
    protected $params;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var WidgetInterface
     */
    protected $wpWidget;

    public function setUp()
    {
        /** @var RenderableInterface $widgetParams */
        $widgetParams = $this->getMockForAbstractClass(RenderableInterface::class);

        $widgetParams->method('getRenderer')->willReturn(
            $this->getMockForAbstractClass(RendererInterface::class)
        );

        $this->params = $widgetParams;
        $this->renderer = $widgetParams->getRenderer();

        $this->wpWidget = $this->getMockForAbstractClass(WidgetInterface::class);
    }

    /**
     * @param array $placeholderParams
     * @param array $settings
     *
     * @dataProvider rendererProvider
     *
     * @return string
     */
    public function testRender(array $placeholderParams = [], array $settings)
    {
        $array = $placeholderParams + ['wpWidget' => $this->wpWidget, 'settings' => $settings];

        $this->params
            ->expects($this->once())
            ->method('getThemeTemplate')
            ->willReturn('string');

        $this->renderer
            ->expects($this->once())
            ->method('render')
            ->with($this->equalTo('string'), $array);

        $this->assertArraySubset(
            ['wpWidget' => $this->getMockForAbstractClass(WidgetInterface::class),
             'settings' => []], $array);

        $this->renderer->render($this->params->getThemeTemplate(), $array);
    }

    public function rendererProvider()
    {
        return [
            [[], []]
        ];
    }


}