<?php
/*
Plugin Name: Sarcofag
Plugin URI: http://milsdev.com/#portfolio
Description: OOP wrapper for the WordPress
Version: 0.0-alpha
Author: Mil's
Author URI: http://milsdev.com/
*/

$loader = include ABSPATH . '/vendor/autoload.php';

$loader->setPsr4('Sarcofag\\', [ __DIR__ . '/src' ]);

if (is_dir(get_template_directory() . '/src/api')) {
    $loader->setPsr4('Api\\', [get_template_directory() . '/src/api']);
}
$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/config/di.inc.php');

if (file_exists(get_template_directory() . '/src/config/di.inc.php')) {
    $containerBuilder->addDefinitions(require get_template_directory() . '/src/config/di.inc.php');
}

$di = $containerBuilder->build();

$di->get('EventManager');
