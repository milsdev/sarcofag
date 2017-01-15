<?php
namespace Sarcofag\Entity;

/**
 * Interface RoutePostEntityInterface
 *
 * Define the contract for entity
 * used in route registration process.
 *
 * @package Sarcofag\Entity
 */
interface RoutePostEntityInterface
{
    /**
     * Post identifier to
     * be used as post_id
     * and will be available
     * for usage in route handlers.
     *
     * @return number
     */
    public function getId();

    /**
     * Full path to the controller
     * which will handle routing request
     * and receive an object with
     * post_id
     *
     * @return string
     */
    public function getController();

    /**
     * Permalink of the post associated
     * via ID with current entity.
     *
     * example: parse_url(get_permalink($postId), PHP_URL_PATH),
     *
     * @return string
     */
    public function getUrl();
}
