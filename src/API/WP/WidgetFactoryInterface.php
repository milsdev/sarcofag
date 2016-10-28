<?php

namespace Sarcofag\API\WP;

use DI\FactoryInterface;
use Sarcofag\SPI\EventManager\Action\ActionInterface;

interface WidgetFactoryInterface extends ActionInterface
{
    public function __construct(FactoryInterface $factory);

    public function register($widget_class, $widget_class_name = '');

    public function unregister($widget_class, $widget_class_name = '');
}