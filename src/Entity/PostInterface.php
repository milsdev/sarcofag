<?php
namespace Sarcofag\Entity;

interface PostInterface
{
    /**
     * Retrieve WP_Post instance.
     *
     * @static
     * @access public
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param int $post_id Post ID.
     * @return WP_Post|false Post object, false otherwise.
     */
    public static function get_instance($post_id);

    /**
     * Isset-er.
     *
     * @param string $key Property to check if set.
     * @return bool
     */
    public function __isset($key);

    /**
     * Getter.
     *
     * @param string $key Key to get.
     * @return mixed
     */
    public function __get($key);

    /**
     * {@Missing Summary}
     *
     * @param string $filter Filter.
     * @return self|array|bool|object|WP_Post
     */
    public function filter($filter);

    /**
     * Convert object to array.
     *
     * @return array Object as array.
     */
    public function to_array();
}
