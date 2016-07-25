<?php
namespace Sarcofag\SPI\Widget;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\API\WP;
use Sarcofag\API\WP\Widget as WPWidget;
use Sarcofag\SPI\Widget\Params\RenderableInterface;
use Sarcofag\View\Renderer\RendererInterface;

class ACFWidget extends GenericWidget
{
    /**
     * @var WP
     */
    protected $wpService;

    /**
     * ACFWidget constructor.
     *
     * @param RenderableInterface $widgetParams
     * @param WPWidget $wpWidget
     * @param WP $wpService
     * @param FiltrationInterface|null $filtrationService
     */
    public function __construct(RenderableInterface $widgetParams,
                                WPWidget $wpWidget,
                                WP $wpService,
                                FiltrationInterface $filtrationService = null)
    {
        parent::__construct($widgetParams, $wpWidget, $filtrationService);
        $this->wpService = $wpService;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    protected function getField($name)
    {
        return $this->wpService->get_field($name, 'widget_'.$this->wpWidget->id);
    }

    /**
     * @param array $placeholderParams
     * @param array $settings
     *
     * @return string
     */
    public function render(array $placeholderParams = [], array $settings)
    {
        return $this->renderer->render($this->params->getThemeTemplate(),
            $placeholderParams + ['wpWidget' => $this->wpWidget,
                                  'settings' => $settings,
                                  'getField' => function ($name){return $this->getField($name);}]);
    }
}
