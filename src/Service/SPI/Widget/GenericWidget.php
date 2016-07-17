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
     * GenericWidget constructor.
     *
     * @param string $widgetName
     * @param string $adminTemplate
     * @param string $themeTemplate
     * @param FiltrationInterface $filtrationService
     * @param RendererInterface $renderer
     */
    public function __construct($widgetName,
                                $adminTemplate,
                                $themeTemplate,
                                FiltrationInterface $filtrationService,
                                RendererInterface $renderer)
    {
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
     * @param array $oldSettings
     * @param array $newSettings
     *
     * @return array Return filtered settings
     */
    public function filter(WPWidget $wpWidget, $oldSettings, $newSettings)
    {
        return $this->filtrationService->filter($oldSettings, $newSettings);
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
