<?php
namespace Sarcofag\SPI\Widget;

use Sarcofag\API\WP\Widget as WPWidget;
use Sarcofag\SPI\Widget\Params\BasicInterface;
use Sarcofag\SPI\Widget\Params\RenderableInterface;
use Sarcofag\View\Renderer\RendererInterface;

class GenericWidget implements WidgetInterface, PersistableInterface
{
    /**
     * @var FiltrationInterface
     */
    protected $filtrationService;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var WPWidget
     */
    protected $wpWidget;

    /**
     * @var RenderableInterface
     */
    protected $params;
    
    /**
     * GenericWidget constructor.
     *
     * @param RenderableInterface $widgetParams
     * @param WPWidget $wpWidget
     * @param FiltrationInterface|null $filtrationService
     */
    public function __construct(RenderableInterface $widgetParams,
                                WPWidget $wpWidget,
                                FiltrationInterface $filtrationService = null)
    {
        $this->params = $widgetParams;
        $this->filtrationService = $filtrationService;
        $this->renderer = $widgetParams->getRenderer();
        $this->wpWidget = $wpWidget;
    }

    /**
     * Method return filtered settings
     * to persist for current WP Widget.
     *
     * @param array $newSettings
     * @param array $oldSettings
     *
     * @return array Return filtered settings
     */
    public function filter($newSettings, $oldSettings)
    {
        if (is_null($this->filtrationService)) {
            return $newSettings;
        }

        return $this->filtrationService->filter($newSettings, $oldSettings);
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
                                                              'settings' => $settings]);
    }

    /**
     * @return BasicInterface
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $settings
     *
     * @return string
     */
    public function renderForm($settings)
    {
        if ($this->params->getAdminTemplate() === false) {
            return '';
        }

        return $this->renderer->render($this->params->getAdminTemplate(),
                                       ['wpWidget' => $this->wpWidget, 'settings' => $settings]);
    }
}
