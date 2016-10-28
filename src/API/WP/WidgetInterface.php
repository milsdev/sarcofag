<?php

namespace Sarcofag\API\WP;

use DI\Container;

interface WidgetInterface
{
    public function __construct($widgetClassNameOrAlias, Container $container);

    public function getId();

    public function widget($args, $instance);

    public function update($newSettings, $oldSettings);

    public function form($settings);
}