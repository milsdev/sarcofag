<?php
namespace Sarcofag\API\WP;

use DI\FactoryInterface;
use Sarcofag\Exception\RuntimeException;
use Sarcofag\SPI\Widget\PersistableInterface;
use Sarcofag\SPI\Widget\FiltrationInterface;
use Sarcofag\SPI\Widget\WidgetInterface;
use Sarcofag\View\Renderer\RendererInterface;

/**
 * Class Widget
 *
 * @package Sarcofag\API\WP
 */
final class Widget extends \WP_Widget
{
    /**
     * @HACK
     * @FIXME PLEASE SOMETIMES IN FUTURE
     * It is hacky property. So i need to have a closure which
     * will return real instance of the created WidgetInterface instance
     * because Wordpress when checking is_active_widget it is uses
     * construction like == in expression $wp_registered_widgets[$widget]['callback'] == $callback
     * and it is couse an error like [Fatal error: Nesting level too deep - recursive dependency?]
     * It is a bit described here http://stackoverflow.com/questions/3834791/fatal-error-nesting-level-too-deep-recursive-dependency
     * So for now, i have to hide from object the instance via closure, because closure
     * does not expose it is context.
     *
     * @var \Closure
     * @return WidgetInterface
     */
    private $getInstance;

    /**
     * Widget constructor.
     *
     * @param string $widgetClassNameOrAlias
     * @param FactoryInterface $factory
     */
    public function __construct($widgetClassNameOrAlias, FactoryInterface $factory)
    {
        $instance = $factory->make($widgetClassNameOrAlias, ['wpWidget'=>$this]);

        if (!in_array(WidgetInterface::class, class_implements($instance))) {
            throw new RuntimeException("Incorrect widget class name or widget does not implement WidgetInterface");
        }

        $this->getInstance = function () use ($instance) {
            return $instance;
        };

        $params = $instance->getParams();

        parent::__construct($params->getId(),
                            $params->getName(),
                            $params->getOptions());
    }

    public function getId()
    {
        $getInstance = $this->getInstance;
        return $getInstance()->getParams()->getId();
    }

    /**
     * @see \WP_Widget::widget()
     *
     * @param $args
     * @param $instance
     */
    public function widget( $args, $instance )
    {
        $getInstance = $this->getInstance;
        echo $getInstance()->render($args, $instance);
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
        $getInstance = $this->getInstance;
        $instance = $getInstance();
        if ($instance instanceof FiltrationInterface) {
            return $instance->filter($newSettings, $oldSettings);
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
        $getInstance = $this->getInstance;
        $instance = $getInstance();
        if (!$instance instanceof PersistableInterface) {
            echo '<p class="no-options-widget">' . __('There are no options for this widget.') . '</p>';
            return 'noform';
        } else {
            echo $instance->renderForm($settings);
        }
    }
}
