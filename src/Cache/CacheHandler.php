<?php

namespace Sarcofag\Cache;

use Sarcofag\API\WP;
use Sarcofag\SPI\Factory\RoutePostEntityFactoryInterface;
use Zend\Cache\StorageFactory;

class CacheHandler
{
    /**
     * @var WP
     */
    protected $wp;

    /**
     * @var \Zend\Cache\Storage\StorageInterface
     */
    protected $cacheStorage;

    /**
     * @var RoutePostEntityFactoryInterface
     */
    protected $routePostEntityFactory;

    /**
     * @var string
     */
    protected $cacheKey = 'posts_to_generate_routes';

    /**
     * CacheHandler constructor.
     * @param WP $wp
     * @param RoutePostEntityFactoryInterface $routePostEntityFactory
     */
    public function __construct(WP $wp, RoutePostEntityFactoryInterface $routePostEntityFactory)
    {
        $this->wp = $wp;
        $this->routePostEntityFactory = $routePostEntityFactory;

        $this->cacheStorage = null;
        if (defined("SARCOFAG_CACHE_PARAMS")) {
            $this->cacheStorage = StorageFactory::factory(SARCOFAG_CACHE_PARAMS);
        }
    }

    /**
     * @param int $postId
     * @param \WP_Post $post
     */
    public function publishPost($postId, $post)
    {
        $items = $this->cacheStorage->getItem($this->cacheKey);

        array_push($items, $this->routePostEntityFactory->create($post->to_array()));
        $this->cacheStorage->replaceItem($this->cacheKey, $items);
    }

    /**
     * @param int $postId
     * @param \WP_Post $postAfter
     * @param \WP_Post $postBefore
     */
    public function updatePost($postId, \WP_Post $postAfter, \WP_Post $postBefore)
    {
        if (spl_object_hash($postBefore) !== spl_object_hash($postAfter)) {
            $items = $this->cacheStorage->getItem($this->cacheKey);

            $post = null;
            foreach ($items as $key => $item) {
                if ($item->getId() === $postId) {
                    unset($items[$key]);
                }
            }

            array_push($items, $this->routePostEntityFactory->create($postAfter->to_array()));
            $this->cacheStorage->replaceItem($this->cacheKey, $items);
        }
    }

    /**
     * @param int $postId
     */
    public function deletePost($postId)
    {
        $items = $this->cacheStorage->getItem($this->cacheKey);
        $deleteId = $this->wp->get_post($postId)->post_parent;

        foreach ($items as $key => $item) {
            if ($item->getId() === $deleteId) {
                unset($items[$key]);
            }
        }
        $this->cacheStorage->replaceItem($this->cacheKey, $items);
    }
}