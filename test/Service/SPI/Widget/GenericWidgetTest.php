<?php

namespace SarcofagTest\Service\SPI\Widget;

use Sarcofag\API\WP\Widget;
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
     * @var Widget
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

//        $this->wpWidget = $this->getMockBuilder(Widget::class);
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
//        $this->renderer->render($this->params->getThemeTemplate(),
//                                $placeholderParams + ['wpWidget' => $this->wpWidget,
//                                                      'settings' => $settings]);
    }

    public function rendererProvider()
    {
        return [
            [[], []]
        ];
    }


}