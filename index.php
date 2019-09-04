<?php
/*
Plugin Name: Sarcofag
Plugin URI: http://milsdev.com/#portfolio
Description: OOP wrapper for the WordPress
Version: 0.0-alpha
Author: Mil's
Author URI: http://milsdev.com/
*/
define('TIMER_DIFF_INIT_WP', microtime(true) - TIMER_RUN);

define('TIMER_INIT_SARCOFAG', microtime(true));
$loader = include ABSPATH . '/vendor/autoload.php';

$loader->setPsr4('Sarcofag\\', [ __DIR__ . '/src' ]);

$cacheStorage = null;
if (defined("SARCOFAG_CACHE_PARAMS")) {
    $cacheStorage = \Zend\Cache\StorageFactory::factory(SARCOFAG_CACHE_PARAMS);
}

if (!is_null($cacheStorage) && $cacheStorage->hasItem('diContainer')) {
    $di = $cacheStorage->getItem('diContainer');
} else {
    $containerBuilder = new DI\ContainerBuilder();
    $containerBuilder->addDefinitions(['DefaultCacheStorage' => $cacheStorage]);

    $definitions   = [];
    $definitions[] = new \DI\Definition\Source\DefinitionFile(__DIR__ . '/config/di.inc.php');

    $activePlugins = get_option('active_plugins');
    foreach ($activePlugins as $activePlugin) {
        $pluginDiConfig = WP_PLUGIN_DIR . '/' . trim(dirname($activePlugin), '/') . '/config/di.inc.php';
        if (!file_exists($pluginDiConfig)) {
            continue;
        }
        $definitions[] = new \DI\Definition\Source\DefinitionFile($pluginDiConfig);
    }

    $iterator = new RegexIterator(new IteratorIterator(
        new DirectoryIterator(get_template_directory() . '/src/config')),
        '/^di\..*inc\.php$/i',
        RegexIterator::MATCH);

    /* @var $iteratorItem \DirectoryIterator */
    foreach ($iterator as $iteratorItem) {
        $definitions[] = new \DI\Definition\Source\DefinitionFile($iteratorItem->getRealPath());

    }

    array_map([$containerBuilder, 'addDefinitions'], $definitions);

    define('TIMER_SARCOFAG_BUILD', microtime(true));
    $di = $containerBuilder->build();
    define('TIMER_DIFF_SARCOFAG_BUILD', microtime(true) - TIMER_SARCOFAG_BUILD);

    if (!is_null($cacheStorage)) {
        $cacheStorage->setItem('diContainer', $di);
    }
}

foreach ($di->get('autoloader.paths') as $namespace => $autoloaderPaths) {
    if (is_array($autoloaderPaths)) {
        $autoloaderPaths = array_map('realpath', $autoloaderPaths);
    } else {
        $autoloaderPaths = [realpath($autoloaderPaths)];
    }

    $loader->setPsr4($namespace, $autoloaderPaths);
}
define('TIMER_DIFF_INIT_SARCOFAG', microtime(true) - TIMER_INIT_SARCOFAG);

define('TIMER_INIT_EVENT_MANAGER', microtime(true));
$di->get('EventManager');
define('TIMER_DIFF_INIT_EVENT_MANAGER', microtime(true) - TIMER_INIT_EVENT_MANAGER);

define('TIMER_SARCOFAG_INITIALIZED', microtime(true));
