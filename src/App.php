<?php
namespace Sarcofag;

use DI;
use Sarcofag\API\WP;
use Sarcofag\Proxy\PostObjectProxy;
use Slim;
use Sarcofag\Admin\CustomFields\ControllerPageMappingField;
use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\EventManager\ListenerInterface;
use Zend\Cache\PatternFactory;
use Zend\Cache\Storage\Adapter\Apc;
use Zend\Cache\Storage\Adapter\Memcache;
use Zend\Cache\Storage\Adapter\Memcached;
use Zend\Cache\StorageFactory;

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
     * @var WP
     */
    protected $wpService;

    /**
     * Item key, which will contains
     * in cache storage data which
     * using for generate routes and
     * register in Slim/APP
     *
     * @var string
     */
    protected $cacheKeyForRoutes = "posts_to_generate_routes";

    /**
     * Array of the settings declared
     * in settings.inc.php array and
     * provided via DI Container. It is
     * store global settings for Slim/App
     * and Sarcofag specific as well.
     *
     * @var array
     */
    protected $settings;

    /**
     * App constructor.
     *
     * @param DI\FactoryInterface $factory
     * @param Slim\App $slimApp
     * @param WP $wpService
     */
    public function __construct(DI\FactoryInterface $factory,
                                Slim\App $slimApp,
                                WP $wpService)
    {
        $this->factory = $factory;
        $this->app = $slimApp;
        $this->wpService = $wpService;
        $this->settings = $slimApp->getContainer()->get('settings');
    }



    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners()
    {
        return [
            $this->factory->make('ActionListener', [
                'names' => 'template_include',
                'callable' => function () { return $this->routeDispatcher(); },
                'priority' => 99
            ])
        ];
    }

    /**
     * Initialize cache, the common cache manager
     * will be used to store all data which might
     * be cached.
     */
    protected function initCache()
    {
        if (!array_key_exists('cache', $this->settings)) {
            return false; // Cache is not exists in configuration
        }

        return StorageFactory::factory($this->settings['cache']);
    }

    /**
     * Fetch all entries according to configured
     * post types, to have a route builtin and
     * configured in Slim/App to become navigable.
     *
     * @return array
     */
    protected function getAllEntriesToBuildRoutes()
    {
        $cache = $this->initCache();
        if ($cache !== false && $cache->hasItem($this->cacheKeyForRoutes)) {
            return $cache->getItem($this->cacheKeyForRoutes); // array
        }

        $items = [];
        $postTypeSettings = $this->app->getContainer()->get('postTypes');

        foreach ($postTypeSettings as $postType=>$postTypeOptions) {
            // Getting all entries, it is mixed posts and pages, and
            // every post type which were defined in postTypes in settings.inc.php
            // but only from list of supported types (https://codex.wordpress.org/Class_Reference/WP_Query#Type_Parameters)
            $entries = $this->wpService->get_posts(['numberposts' => -1, 'post_type' => $postType]);

            // Fetching default controller to be able to use it if
            // any controller were mentioned while POST were created
            $defaultController = $postTypeOptions['defaultController'];

            $controllerPageMapping = $this->app->getContainer()->get(ControllerPageMappingField::class);

            // Extract from full post data only required for the
            // route building params. Merge all the entries of all the types.
            // And this params will be cached if cache enabled.
            $items = array_merge($items,
                        array_map(function ($post) use ($controllerPageMapping, $defaultController) {
                            // Get a field from post where defined which controller class
                            // should be associated with current POST/PAGE
                            $controller = $controllerPageMapping->getValue($post->ID);
                            return ['id' => $post->ID,
                                    'url' => parse_url($this->wpService->get_permalink($post),
                                                        PHP_URL_PATH),
                                    'controller'=> empty($controller) ?
                                                        $defaultController :
                                                        $controller];
            }, $entries));
        }

        if ($cache !== false) {
            $cache->setItem($this->cacheKeyForRoutes, $items);
        }
        return $items;
    }

    /**
     * Create all routes and register
     * them in Slim/App.
     */
    protected function routeDispatcher()
    {
        $container = $this->app->getContainer();
        foreach ($this->getAllEntriesToBuildRoutes() as $route) {
            $this->app->map(['get', 'post'], $route['url'], $container->get($route['controller']))
                      ->setArgument('requestedEntity',
                                        // Create a dummy proxy for the
                                        // WP_Post object. And pass this proxy to all
                                        // routes
                                        new PostObjectProxy($route['id'], $this->wpService));
        }

        $this->app->run();
    }
}
