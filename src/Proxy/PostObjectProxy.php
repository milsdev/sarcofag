<?php
namespace Sarcofag\Proxy;

use Sarcofag\API\WP;
use Sarcofag\Entity\PostInterface;
use Sarcofag\Proxy\Exception\NotLoadableException;

class PostObjectProxy implements PostInterface
{
    /**
     * @var number
     */
    private $id;

    /**
     * @var WP
     */
    protected $wpService;

    /**
     * Real post object, which contains
     * a real data from database.
     * By Default it is null, if it is
     * were not. But when
     * it will loaded it will become
     * plain object or \WP_Post
     *
     * @var null | \WP_Post | \StdClass
     */
    protected $proxyingData = null;

    /**
     * PostObjectProxy constructor.
     *
     * @param $postId
     * @param WP $wpService
     */
    public function __construct($postId, WP $wpService)
    {
        $this->id = $postId;
        $this->wpService = $wpService;
    }

    /**
     * Do a real load post by it is id from the
     * database.
     * BE CAREFUL: This operation will really do a Query to DB.
     *
     * @throws NotLoadableException If post with given in constructor IDENTIFIER could not be loaded.
     */
    protected function loadPost()
    {
        if (is_null($this->proxyingData)) {
            $post = $this->wpService->get_post($this->id);
            if (!$post) {
                throw new NotLoadableException("Post object with id {$this->id} could not " .
                                               "be loaded ot the proxy");
            }

            $this->proxyingData = $post;
        }
    }

    /**
     * Getting params from the loaded
     * \WP_Post object.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $this->loadPost();
        return $this->proxyingData->{$name};
    }

    /**
     * Retrieve WP_Post instance.
     *
     * @static
     * @access public
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param int $post_id Post ID.
     *
     * @return WP_Post|false Post object, false otherwise.
     */
    public static function get_instance($post_id)
    {
        return \WP_Post::get_instance($post_id);
    }

    /**
     * Isset-er.
     *
     * @param string $key Property to check if set.
     *
     * @return bool
     */
    public function __isset($key)
    {
        $this->loadPost();
        return $this->proxyingData->__isset($key);
    }

    /**
     * {@Missing Summary}
     *
     * @param string $filter Filter.
     *
     * @return self|array|bool|object|WP_Post
     */
    public function filter($filter)
    {
        $this->loadPost();
        return $this->proxyingData->filter($filter);
    }

    /**
     * Convert object to array.
     *
     * @return array Object as array.
     */
    public function to_array()
    {
        $this->loadPost();
        return $this->proxyingData->to_array();
    }

    /**
     * Setting params to the loaded
     * \WP_Post object.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->loadPost();
        $this->proxyingData->{$name} = $value;
    }
}
