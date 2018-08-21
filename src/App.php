<?php
namespace Sarcofag;

use DI;
use Sarcofag\Cache\CacheHandler;
use Slim;
use Sarcofag\API\WP;
use Zend\Cache\Storage\StorageInterface;
use Sarcofag\Entity\RoutePostEntityInterface;
use Sarcofag\SPI\EventManager\ListenerInterface;
use Sarcofag\SPI\Factory\RoutePostEntityFactory;
use Sarcofag\SPI\Routing\RoutePostFilterInterface;
use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\Factory\RoutePostEntityFactoryInterface;

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
    protected $cacheKeyForRoutes = CacheHandler::ROUTE_CACHE_KEY;

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
     * Filters collection to filtering routes
     * by rules defined inside the filters logic.
     *
     * @var RoutePostFilterInterface
     */
    protected $routePostEntityFilter;

    /**
     * Factory to create a RoutePostEntity
     * instances using the data fetched from
     * WP_Post.
     *
     * @var RoutePostEntityFactory
     */
    protected $routePostEntityFactory;

    /**
     * Optional cache storage. Will
     * be used to cache routes to the storage.
     *
     * @var StorageInterface | null
     */
    protected $cache = null;


    protected $cacheHandler;

    /**
     * App constructor.
     *
     * @param array $settings
     * @param DI\FactoryInterface $factory
     * @param Slim\App $slimApp
     * @param WP $wpService
     * @param RoutePostEntityFactoryInterface $routePostEntityFactory
     * @param RoutePostFilterInterface $routePostEntityFilter
     * @param CacheHandler $cacheHandler
     */
    public function __construct(array $settings,
                                DI\FactoryInterface $factory,
                                Slim\App $slimApp,
                                WP $wpService,
                                CacheHandler $cacheHandler,
                                RoutePostEntityFactoryInterface $routePostEntityFactory,
                                RoutePostFilterInterface $routePostEntityFilter)
    {
        $this->app = $slimApp;
        $this->factory = $factory;
        $this->settings = $settings;
        $this->wpService = $wpService;
        $this->routePostEntityFilter = $routePostEntityFilter;
        $this->routePostEntityFactory = $routePostEntityFactory;
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners()
    {
        $listeners = [
            $this->factory->make('ActionListener', [
                'names' => 'template_include',
                'callable' => function () {
                    return $this->routeDispatcher();
                },
                'priority' => 99
            ])
        ];

        if ($this->cache !== null) {
            $listeners[] = $this->factory->make('ActionListener', [
                'names' => ['publish_post'],
                'callable' => function($postId, $post) {
                    $this->cacheHandler->publishPost($postId, $post);
                },
                'priority' => 10,
                'argc' => 2
            ]);

            $listeners[] = $this->factory->make('ActionListener', [
                'names' => ['post_updated'],
                'callable' => function($postId, $postAfter, $postBefore) {
                    $this->cacheHandler->updatePost($postId, $postAfter, $postBefore);
                },
                'priority' => 10,
                'argc' => 3
            ]);

            $listeners[] = $this->factory->make('ActionListener', [
                'names' => ['delete_post'],
                'callable' => function($postId) {
                    $this->cacheHandler->deletePost($postId);
                },
                'priority' => 10,
            ]);
        }

        return $listeners;
    }

    /**
     * @param StorageInterface $storage [OPTIONAL
     */
    public function setCache(StorageInterface $storage = null)
    {
        $this->cache = $storage;
    }

    /**
     * Fetch all entries according to configured
     * post types, to have a route builtin and
     * configured in Slim/App to become navigable.
     *
     * @return RoutePostEntityInterface[]
     */
    protected function getAllEntriesToBuildRoutes()
    {
        if ($this->cache !== null && $this->cache->hasItem($this->cacheKeyForRoutes)) {
            $items = $this->cache->getItem($this->cacheKeyForRoutes); // array
            return $items;
        }

        $items = [];

        $postTypeSettings = $this->app->getContainer()->get('postTypes');

        foreach ($postTypeSettings as $postType=>$postTypeOptions) {
            // Getting all entries, it is mixed posts and pages, and
            // every post type which were defined in postTypes in settings.inc.php
            // but only from list of supported types
            // (https://codex.wordpress.org/Class_Reference/WP_Query#Type_Parameters)
            $entries = $this->wpService->get_posts(['numberposts' => - 1,
                                                    'post_type' => $postType]);

            // Extract from full post data only required for the
            // route building params. Merge all the entries of all the types.
            // And this params will be cached if cache enabled.
            $items = array_merge($items,
                        array_map(function ($post) {
                            // Create a RoutePostEntityInterface object
                            // to use it while registering Routes in Slim/App
                            $entity = $this->routePostEntityFactory
                                           ->create($post->to_array());
                            return $entity;
                        }, $entries));
        }

        if ($this->cache !== null) {
            $this->cache->setItem($this->cacheKeyForRoutes, $items);
        }
        return $items;
    }

    /**
     * Create all routes and register
     * them in Slim/App.
     */
    protected function routeDispatcher()
    {
//        define('TIMER_BEGIN_ROUTE_DISPATCHER', microtime());
//        define('DIFF_BEGIN_ROUTE_DISPATCHER', microtime() - TIMER_BEGIN_WP_EXECUTION);

        $container = $this->app->getContainer();
        $wpService = $this->wpService;
        foreach ($this->getAllEntriesToBuildRoutes() as $routePostEntity) {

            // Run filter to remove posts from the
            // queue to be registered as a routes.
            if (!$this->routePostEntityFilter->filter($routePostEntity)) continue;

            $this->app->map(['get', 'post'], $routePostEntity->getUrl(),
                            function ($request, $response, $args)
                                use ($container, $routePostEntity, $wpService) {

                // Setup requested entity as the
                // current post and put it to the
                // global scope, because it is common for WP
                global $post;
                $post = $wpService->get_post($routePostEntity->getId());
                $wpService->setup_postdata($post);

                $invokableController = $container->get($routePostEntity->getController());
                return $invokableController($request, $response,
                                            array_merge($args,
                                                        ['requestedEntity'=> $post]));
            });
        }

//        define('TIMER_BEFORE_APP_RUN', microtime());
//        define('DIFF_BEFORE_APP_RUN', microtime() - TIMER_BEGIN_ROUTE_DISPATCHER);
        $this->app->run();

//        define('TIMER_AFTER_APP_RUN', microtime());
//        define('DIFF_AFTER_APP_RUN', microtime() - TIMER_BEFORE_APP_RUN);
    }


}
