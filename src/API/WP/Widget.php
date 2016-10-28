<?php
namespace Sarcofag\API\WP;

use DI\Container;
use Sarcofag\Exception\RuntimeException;
use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\EventManager\DataFilter\DataFilterInterface;
use Sarcofag\SPI\EventManager\EventManagerInterface;
use Sarcofag\SPI\Widget\Params\ControlableInterface;
use Sarcofag\SPI\Widget\PersistableInterface;
use Sarcofag\SPI\Widget\FiltrationInterface;
use Sarcofag\View\Renderer\RendererInterface;

/**
 * Class Widget
 *
 * @package Sarcofag\API\WP
 */
final class Widget extends \WP_Widget implements WidgetInterface
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
     * @param Container $container
     */
    public function __construct($widgetClassNameOrAlias,
                                Container $container)
    {
        $instance = $container->make($widgetClassNameOrAlias, ['wpWidget'=>$this]);

        if (!in_array(WidgetInterface::class, class_implements($instance))) {
            throw new RuntimeException("Incorrect widget class name or widget does not implement WidgetInterface");
        }

        /**
         * Check if instance have listeners for wordpress
         * events which should be registered while
         * widget registering.
         */
        if ($instance instanceof ActionInterface || $instance instanceof DataFilterInterface) {
            $container->get('EventManager')->attachListeners($instance);
        }

        $this->getInstance = function () use ($instance) {
            return $instance;
        };

        $params = $instance->getParams();

        $controlOption = [];
        if ($params instanceof ControlableInterface) {
            $controlOption = $params->getControlOptions();
        }

        parent::__construct($params->getId(),
                            $params->getName(),
                            $params->getOptions(),
                            $controlOption);
    }

    /**
     * FIXME: It is return not a real unique Widget ID
     * it is return a widget registration name, which you
     * declare in DI while registering your own widget.
     * On example when you register widget you define my_widget_super_cool,
     * it is NAME of the widget, which WP will use to generate unique id like
     * my_widget_super_cool-1, so to get this id you will have to call PUBLIC
     * PROPERTY ->id. So it is behaviour should be fixed, because it is
     * really confusing.
     *
     * @return mixed
     */
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
