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

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/config/di.inc.php');

$activePlugins = get_option('active_plugins');
foreach ($activePlugins as $activePlugin) {
    $pluginDiConfig = WP_PLUGIN_DIR . '/' . trim(dirname($activePlugin), '/') . '/config/di.inc.php';
    if (!file_exists($pluginDiConfig)) continue;
    $containerBuilder->addDefinitions(require $pluginDiConfig);
}

if (file_exists(get_template_directory() . '/src/config/di.inc.php')) {
    $containerBuilder->addDefinitions(require get_template_directory() . '/src/config/di.inc.php');
}

$di = $containerBuilder->build();

foreach ($di->get('autoloader.paths') as $namespace => $autoloaderPaths) {
    if (is_array($autoloaderPaths)) {
        $autoloaderPaths = array_map('realpath', $autoloaderPaths);
    } else {
        $autoloaderPaths = [realpath($autoloaderPaths)];
    }

    $loader->setPsr4($namespace, $autoloaderPaths);
}

$di->get('EventManager');
