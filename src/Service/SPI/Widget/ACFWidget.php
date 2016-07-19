<?php
namespace Sarcofag\Service\SPI\Widget;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\Service\API\WP;
use Sarcofag\Service\API\WP\Widget as WPWidget;
use Sarcofag\Service\SPI\Widget\Params\RenderableInterface;
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
}
