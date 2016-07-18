<?php
namespace Sarcofag\Service\SPI\Widget;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\Service\API\WP\Widget as WPWidget;
use Sarcofag\View\Renderer\RendererInterface;

class GenericWidget implements WidgetInterface, PersistableInterface
{
    /**
     * @var string
     */
    protected $adminTemplate;

    /**
     * @var string
     */
    protected $themeTemplate;

    /**
     * @var FiltrationInterface
     */
    protected $filtrationService;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $widgetName;

    /**
     * @var string
     */
    protected $widgetId;

    /**
     * GenericWidget constructor.
     *
     * @param string $widgetId
     * @param string $widgetName
     * @param string $adminTemplate
     * @param string $themeTemplate
     * @param FiltrationInterface $filtrationService [OPTIONAL]
     * @param RendererInterface $renderer
     */
    public function __construct($widgetId,
                                $widgetName,
                                $adminTemplate,
                                $themeTemplate,
                                RendererInterface $renderer,
                                FiltrationInterface $filtrationService = null)
    {
        $this->widgetId = $widgetId;
        $this->widgetName = $widgetName;
        $this->adminTemplate = $adminTemplate;
        $this->themeTemplate = $themeTemplate;
        $this->filtrationService = $filtrationService;
        $this->renderer = $renderer;
    }

    /**
     * Method return filtered settings
     * to persist for current WP Widget.
     *
     * @param WPWidget $wpWidget
     * @param array $newSettings
     * @param array $oldSettings
     *
     * @return array Return filtered settings
     */
    public function filter(WPWidget $wpWidget, $newSettings, $oldSettings)
    {
        if (is_null($this->filtrationService)) {
            return $newSettings;
        }

        return $this->filtrationService->filter($wpWidget, $newSettings, $oldSettings);
    }

    /**
     * Return the name of the widget
     *
     * @return string
     */
    public function getName()
    {
        return $this->widgetName;
    }

    /**
     * Return the id of the widget
     *
     * @return string
     */
    public function getId()
    {
        return $this->widgetId;
    }


    /**
     * @param WPWidget $wpWidget
     * @param array $placeholderParams
     * @param array $settings
     *
     * @return string
     */
    public function render(WPWidget $wpWidget, array $placeholderParams = [], array $settings)
    {
        return $this->renderer->render($this->themeTemplate, 
                                        $placeholderParams + ['wpWidget' => $wpWidget, 
                                                              'settings' => $settings]);
    }

    /**
     * @param WPWidget $wpWidget
     * @param array $settings
     *
     * @return string
     */
    public function renderForm(WPWidget $wpWidget, $settings)
    {
        return $this->renderer->render($this->adminTemplate, ['wpWidget' => $wpWidget,
                                                              'settings' => $settings]);
    }
}
