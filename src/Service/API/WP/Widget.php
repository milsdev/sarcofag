<?php
namespace Sarcofag\Service\API\WP;

use Sarcofag\Service\SPI\Widget\PersistableInterface;
use Sarcofag\Service\SPI\Widget\FiltrationInterface;
use Sarcofag\Service\SPI\Widget\WidgetInterface;
use Sarcofag\View\Renderer\RendererInterface;

/**
 * Class Widget
 *
 * @package Sarcofag\Service\API\WP
 */
final class Widget extends \WP_Widget
{
    /**
     * @var WidgetInterface
     */
    protected $instance;

    /**
     * Widget constructor.
     *
     * @param string $widgetId
     * @param string $widgetName
     * @param array $widgetOptions
     * @param WidgetInterface $widget
     */
    public function __construct($widgetId, $widgetName, array $widgetOptions, WidgetInterface $widget)
    {
        $this->instance = $widget;
        parent::__construct($widgetId, $widgetName, $widgetOptions);
    }

    /**
     * @see \WP_Widget::widget()
     *
     * @param $args
     * @param $instance
     */
    public function widget( $args, $instance )
    {
        echo $this->instance->render($this, $args, $instance);
    }

    /**
     * @see \WP_Widget::update()
     *
     * @param $newSettings
     * @param $oldSettings
     *
     * @return array
     */
    public function update( $newSettings, $oldSettings )
    {
        if ($this->instance instanceof FiltrationInterface) {
            return $this->instance->filter($this, $newSettings, $oldSettings);
        } else {
            return $newSettings;
        }
    }

    /**
     * @see \WP_Widget::form()
     *
     * @param $settings
     */
    public function form( $settings )
    {
        if (!$this->instance instanceof PersistableInterface) {
            echo '<p class="no-options-widget">' . __('There are no options for this widget.') . '</p>';
            return 'noform';
        } else {
            echo $this->instance->renderForm($this, $settings);
        }
    }
}
