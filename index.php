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

$cacheStorage = null;
if (defined("SARCOFAG_CACHE_PARAMS")) {
    $cacheStorage = \Zend\Cache\StorageFactory::factory(SARCOFAG_CACHE_PARAMS);
}

$containerBuilder->addDefinitions(['DefaultCacheStorage' => $cacheStorage]);

if (!is_null($cacheStorage) && $cacheStorage->hasItem('diDefinitions')) {
    $definitions = $cacheStorage->getItem('diDefinitions');
} else {
    $definitions = [];
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

    if (!is_null($cacheStorage)) {
        $cacheStorage->setItem('diDefinitions', $definitions);
    }
}

array_map([$containerBuilder, 'addDefinitions'], $definitions);

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
