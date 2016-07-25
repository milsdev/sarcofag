<?php
namespace Sarcofag\API\WP;

use DI\FactoryInterface;
use Sarcofag\Exception\RuntimeException;
use Sarcofag\API\WP;
use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\EventManager\ListenerInterface;

final class WidgetFactory extends \WP_Widget_Factory implements ActionInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * WidgetFactory constructor.
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
        parent::__construct();
    }

    /**
     * @param string $widget_class
     * @param string $widget_class_name
     */
    public function register( $widget_class, $widget_class_name = '' )
    {
        if (is_object($widget_class)) {
            $this->widgets[$widget_class_name] = $widget_class;
        } else {
            parent::register($widget_class);
        }
    }

    /**
     * @param string $widget_class
     * @param string $widget_class_name
     */
    public function unregister( $widget_class, $widget_class_name = '' )
    {
        if (empty($widget_class_name) && is_object($widget_class)) {
            throw new RuntimeException
                    ('If widget class is object then you should define widget_class_name ot unregister it');
        }

        if (!empty($widget_class_name)) {
            unset( $this->widgets[ $widget_class_name ] );
        } else {
            parent::unregister($widget_class);
        }
    }

    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners ()
    {
        return [$this->factory->make('ActionListener',
                                     ['names' => 'setup_theme',
                                      'callable' => function () {
                                            $GLOBALS['wp_widget_factory'] = $this;
                                      }])];
    }
}
