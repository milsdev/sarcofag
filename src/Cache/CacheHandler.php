<?php

namespace Sarcofag\Cache;

use Sarcofag\API\WP;
use Sarcofag\Entity\RoutePostEntityInterface;
use Sarcofag\SPI\Factory\RoutePostEntityFactoryInterface;
use Zend\Cache\Storage\StorageInterface;

class CacheHandler
{
    /**
     * @var WP
     */
    protected $wp;

    /**
     * @var StorageInterface
     */
    protected $cacheStorage;

    /**
     * @var RoutePostEntityFactoryInterface
     */
    protected $routePostEntityFactory;

    /**
     * @var string
     */
    const ROUTE_CACHE_KEY = 'posts_to_generate_routes';

    /**
     * CacheHandler constructor.
     *
     * @param WP $wp
     * @param RoutePostEntityFactoryInterface $routePostEntityFactory
     * @param StorageInterface|null $storage [OPTIONAL]
     */
    public function __construct(WP $wp,
                                RoutePostEntityFactoryInterface $routePostEntityFactory,
                                StorageInterface $storage = null)
    {
        $this->wp = $wp;
        $this->cacheStorage = $storage;
        $this->routePostEntityFactory = $routePostEntityFactory;
    }

    /**
     * This is WP hook listener,
     * which invokes every time
     * when post is PUBLISHED.
     *
     * @param int $postId
     * @param \WP_Post $post
     */
    public function publishPost($postId, $post)
    {
        /**
         * FIXME: Because of architecture lacks
         * we have to do such of things. I mean we
         * have to check if cacheStorage not is null
         * because DI might inject it with NULL value.
         *
         * And i'm really suffer about it. And will refactor
         * this in near future, i promise!
         * Oleksii...
         */
        if (is_null($this->cacheStorage)) {
            return;
        }
        $this->replaceRouteByIdInCache($postId, $post);
    }

    /**
     * Fetch Route Entities from cache and then
     * try to find and filtering out Route with
     * identifier in $routeId, and return modified
     * list of Route Entities.
     *
     * @param string | number $routeId
     *
     * @return RoutePostEntityInterface[]
     */
    protected function getCacheItemsWithoutGivenRoute($routeId)
    {
        $items = $this->cacheStorage->getItem(self::ROUTE_CACHE_KEY);

        // This filter will prevent the situation when
        // you publish one post few times. So it is will
        // remove from cache post with same ID and we will
        // able to add newly fabricated Item to the cache.
        $routesWithoutPublished = array_filter($items,
            function (RoutePostEntityInterface $item) use ($routeId) {
                return $item->getId() != $routeId;
            });

        return $routesWithoutPublished;
    }

    /**
     * @param number $routeId
     * @param \WP_Post $post
     */
    protected function replaceRouteByIdInCache($routeId, \WP_Post $post)
    {
        $routesWithoutPublished = $this->getCacheItemsWithoutGivenRoute($routeId);

        array_push($routesWithoutPublished, $this->routePostEntityFactory->create($post->to_array()));
        $this->cacheStorage->replaceItem(self::ROUTE_CACHE_KEY, $routesWithoutPublished);
    }

    /**
     * @param int $postId
     * @param \WP_Post $postAfter
     * @param \WP_Post $postBefore
     */
    public function updatePost($postId, \WP_Post $postAfter, \WP_Post $postBefore)
    {
        /**
         * FIXME: Because of architecture lacks
         * we have to do such of things. I mean we
         * have to check if cacheStorage not is null
         * because DI might inject it with NULL value.
         *
         * And i'm really suffer about it. And will refactor
         * this in near future, i promise!
         * Oleksii...
         */
        if (is_null($this->cacheStorage)) {
            return;
        }

        if ($postAfter === $postBefore) return;

        if ($postAfter->post_status !== "publish") {
            $routesWithoutDeleted = $this->getCacheItemsWithoutGivenRoute($postId);
            $this->cacheStorage->replaceItem(self::ROUTE_CACHE_KEY, $routesWithoutDeleted);
            return;
        }

        $this->replaceRouteByIdInCache($postId, $postAfter);
    }

    /**
     * This is WP hook, will be called
     * every time when post will be deleted.
     * So we will filter out Route with
     * given ID from cache storage
     *
     * @param int $postId
     */
    public function deletePost($postId)
    {
        /**
         * FIXME: Because of architecture lacks
         * we have to do such of things. I mean we
         * have to check if cacheStorage not is null
         * because DI might inject it with NULL value.
         *
         * And i'm really suffer about it. And will refactor
         * this in near future, i promise!
         * Oleksii...
         */
        if (is_null($this->cacheStorage)) {
            return;
        }

        // Wordpress in $postId, returns new ID when
        // it is delete POST, because it is move it into
        // to the TRASH, so we need to get the newly
        // created POST by it is given id in $postId and
        // fetch it is post_parent property to get
        // removed POST id.
        $deletedId = $this->wp->get_post($postId)->post_parent;
        $routesWithoutDeleted = $this->getCacheItemsWithoutGivenRoute($deletedId);
        $this->cacheStorage->replaceItem(self::ROUTE_CACHE_KEY, $routesWithoutDeleted);
    }
}
