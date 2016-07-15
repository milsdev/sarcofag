<?php
/*
Plugin Name: Sarcofag
Plugin URI: http://milsdev.com/#portfolio
Description: OOP wrapper for the WordPress
Version: 0.0-alpha
Author: Mil's
Author URI: http://milsdev.com/
*/

namespace Sarcofag;

use DI;
use Sarcofag\Admin\CustomFields\ControllerPageMappingField;
use Sarcofag\Service\SPI\RegisterActionInterface;
use Sarcofag\Service\SPI\RegisterFilterInterface;
use Sarcofag\Service\SPI\Widget;
use Slim;

class Sarcofag
{
    /**
     * @var ControllerPageMappingField
     */
    protected $controllerPageMapping = null;

    /**
     * @var DI\Container
     */
    protected $di;

    /**
     * @var Slim\App
     */
    protected $app;

    /**
     * Sarcofag constructor.
     *
     * @param DI\ContainerBuilder $containerBuilder
     */
    public function __construct(DI\ContainerBuilder $containerBuilder)
    {
        $this->initApp($containerBuilder);

        $this->initLang();
        $this->initFields();

        $this->di->get(RegisterActionInterface::class)->registerActions();
        $this->di->get(RegisterFilterInterface::class)->registerFilters();
    }

    protected function observeWPEvents()
    {
//        add_action( 'widgets_init', function () {
//            $this->di->get(Widget\Registry::class);
//            register_sidebar( array(
//                'name'          => 'Home right sidebar',
//                'id'            => 'home_right_1',
//                'before_widget' => '<div>',
//                'after_widget'  => '</div>',
//                'before_title'  => '<h2 class="rounded">',
//                'after_title'   => '</h2>',
//            ) );
//        });
    }

    protected function initLang()
    {
        load_plugin_textdomain( 'slim-framework-skeleton',
                                basename( dirname( __FILE__ ) )  . 'languages',
                                'slim-framework-skeleton/languages' );
    }

    protected function initFields()
    {
        $this->controllerPageMapping = $this->di->get('Sarcofag\Admin\CustomFields\ControllerPageMappingField');
        $this->controllerPageMapping->register();
    }

    /**
     * @param DI\ContainerBuilder $containerBuilder
     */
    protected function initApp(DI\ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addDefinitions(__DIR__ . '/config/di.inc.php');

        $containerBuilder->addDefinitions([
            'DefaultStaticPostController' => 'Sarcofag\Theme\Controller\StaticPostController',
            'DefaultStaticPageController' => 'Sarcofag\Theme\Controller\StaticPostController'
        ]);
        
        if (file_exists(get_template_directory() . '/src/config/di.inc.php')) {
            $containerBuilder->addDefinitions(require get_template_directory() . '/src/config/di.inc.php');
        }
        
        $containerBuilder->addDefinitions([Slim\App::class => function () { return $this->app; }]);

        $this->di = $containerBuilder->build();
        $this->app = new Slim\App($this->di);

        add_action( 'template_include', function () {
            
            foreach (get_pages() as $page) {
                $controller = $this->controllerPageMapping->getValue($page->ID);
                $this->app->get(parse_url(get_permalink($page), PHP_URL_PATH),
                                $this->di->get(empty($controller) ? 'DefaultStaticPageController' : $controller))
                     ->setArgument('requestedEntity', $page);
            }

            foreach (get_posts() as $post) {
                $controller = $this->controllerPageMapping->getValue($post->ID);
                $this->app
                     ->get(parse_url(get_permalink($post), PHP_URL_PATH),
                           $this->di->get(empty($controller) ? 'DefaultStaticPostController' : $controller))
                     ->setArgument('requestedEntity', $post);
            }
            $this->app->run();
        }, 99);
    }
}

$loader = include ABSPATH . '/vendor/autoload.php';

$loader->setPsr4('Sarcofag\\', [ __DIR__ . '/src' ]);

if (is_dir(get_template_directory() . '/src/api')) {
    $loader->setPsr4('Api\\', [get_template_directory() . '/src/api']);
}

new Sarcofag(new DI\ContainerBuilder());
