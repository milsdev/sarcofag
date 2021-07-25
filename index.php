<?php
/*
Plugin Name: Sarcofag
Plugin URI: http://milsdev.com/#portfolio
Description: OOP wrapper for the WordPress
Version: 2.0.2
Author: Mil's
Author URI: http://milsdev.com/
*/

function __registerAutoloaderPaths($loader, $target) {
    if (is_string($target)) {
        if (!file_exists($target)) {
            return false;
        }
        $paths = include $target;
        if (!is_array($paths)) {
            throw new \Exception('Autoloader file must return array of [namespace => path]');
        }
    } else if (is_array($target)) {
        $paths = $target;
    } else {
        throw new \Exception('$target must be string if it is file or array with autoloader paths');
    }

    foreach ($paths as $namespace => $autoloaderPaths) {
        if (is_array($autoloaderPaths)) {
            $autoloaderPaths = array_map('realpath', $autoloaderPaths);
        } else {
            $autoloaderPaths = [realpath($autoloaderPaths)];
        }

        $loader->setPsr4($namespace, $autoloaderPaths);
    }
}

if (defined('TIMER_RUN')) {
    define('TIMER_DIFF_INIT_WP', microtime(true) - TIMER_RUN);
}

define('TIMER_INIT_SARCOFAG', microtime(true));
$loader = include ABSPATH . '/vendor/autoload.php';

$loader->setPsr4('Sarcofag\\', [ __DIR__ . '/src' ]);

$cacheStorage = null;
if (defined("SARCOFAG_CACHE_PARAMS")) {
    $cacheStorage = \Laminas\Cache\StorageFactory::factory(SARCOFAG_CACHE_PARAMS);
}

if (!is_null($cacheStorage) && $cacheStorage->hasItem('diContainerBuilder')) {
    __registerAutoloaderPaths($loader, get_template_directory() . '/src/config/autoloader.inc.php');

    $containerBuilder = $cacheStorage->getItem('diContainerBuilder');
} else {
    $containerBuilder = new DI\ContainerBuilder();

    $definitions   = [];
    $definitions[] = new \DI\Definition\Source\DefinitionFile(__DIR__ . '/config/di.inc.php');

    $activePlugins = get_option('active_plugins');

    foreach ($activePlugins as $activePlugin) {
        $pluginConfigPath = WP_PLUGIN_DIR . '/' . trim(dirname($activePlugin), '/') . '/config';
        $pluginDiConfig = $pluginConfigPath . '/di.inc.php';
        __registerAutoloaderPaths($loader, $pluginConfigPath . '/autoloader.inc.php');

        if (!file_exists($pluginDiConfig)) {
            continue;
        }

        $definitions[] = new \DI\Definition\Source\DefinitionFile($pluginDiConfig);
    }

    $themeConfigPath = get_template_directory() . '/src/config';
    $iterator = new RegexIterator(new IteratorIterator(
        new DirectoryIterator($themeConfigPath)),
        '/^di\..*inc\.php$/i',
        RegexIterator::MATCH);

    __registerAutoloaderPaths($loader, $themeConfigPath . '/autoloader.inc.php');

    /* @var $iteratorItem \DirectoryIterator */
    foreach ($iterator as $iteratorItem) {
        $definitions[] = new \DI\Definition\Source\DefinitionFile($iteratorItem->getRealPath());

    }

    array_map([$containerBuilder, 'addDefinitions'], $definitions);

    if (!is_null($cacheStorage)) {
        $cacheStorage->setItem('diContainerBuilder', $containerBuilder);
    }
}

define('TIMER_SARCOFAG_BUILD', microtime(true));
$containerBuilder->addDefinitions(['DefaultCacheStorage' => $cacheStorage]);
$di = $containerBuilder->build();
define('TIMER_DIFF_SARCOFAG_BUILD', microtime(true) - TIMER_SARCOFAG_BUILD);

__registerAutoloaderPaths($loader, $di->get('autoloader.paths'));


define('TIMER_DIFF_INIT_SARCOFAG', microtime(true) - TIMER_INIT_SARCOFAG);

define('TIMER_INIT_EVENT_MANAGER', microtime(true));

define('ICL_DEFAULT_LANGUAGE_CODE', $di->get('icl.default.language.code'));

$di->get('EventManager');
define('TIMER_DIFF_INIT_EVENT_MANAGER', microtime(true) - TIMER_INIT_EVENT_MANAGER);

define('TIMER_SARCOFAG_INITIALIZED', microtime(true));
