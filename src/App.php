<?php
/*
Plugin Name: Sarcofag
Plugin URI: http://milsdev.com/
Description: OOP wrapper for the WordPress
Version: 0.0-alpha
Author: Mil's
Author URI: http://milsdev.com/
*/

namespace Sarcofag;

use DI;
use Slim;
use Sarcofag\Admin\CustomFields\ControllerPageMappingField;
use Sarcofag\Service\SPI\EventManager\Action\ActionInterface;
use Sarcofag\Service\SPI\EventManager\ListenerInterface;

class App implements ActionInterface
{
    /**
     * @var DI\FactoryInterface
     */
    protected $factory;

    /**
     * @var Slim\App
     */
    protected $app;

    /**
     * App constructor.
     *
     * @param DI\FactoryInterface $factory
     * @param Slim\App $slimApp
     */
    public function __construct(DI\FactoryInterface $factory,
                                    Slim\App $slimApp)
    {
        $this->factory = $factory;
        $this->app = $slimApp;
    }

    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners()
    {
        $routeDispatcher = function () {
            $controllerPageMapping = $this->app->getContainer()->get(ControllerPageMappingField::class);
            foreach (get_pages() as $page) {
                $controller = $controllerPageMapping->getValue($page->ID);
                $this->app->get(parse_url(get_permalink($page), PHP_URL_PATH),
                                $this->app->getContainer()
                                     ->get(empty($controller) ? 'DefaultStaticPageController' : $controller))
                     ->setArgument('requestedEntity', $page);
            }

            foreach (get_posts() as $post) {
                $controller = $controllerPageMapping->getValue($post->ID);
                $this->app
                     ->get(parse_url(get_permalink($post), PHP_URL_PATH),
                            $this->app->getContainer()
                                 ->get(empty($controller) ? 'DefaultStaticPostController' : $controller))
                     ->setArgument('requestedEntity', $post);
            }
            $this->app->run();
        };

        return [
            $this->factory->make('ActionListener', [
                'names' => 'template_include',
                'callable' => $routeDispatcher,
                'priority' => 99
            ])
        ];
    }
}
