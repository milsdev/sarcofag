<?php
namespace Sarcofag\Entity;


/**
 * Class RoutePostEntity
 *
 * @inheritdoc RoutePostEntityInterface
 *
 * @package Sarcofag\Entity
 */
class RoutePostEntity implements RoutePostEntityInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $url;

    /**
     * RoutePostEntity constructor.
     *
     * @param number $id
     * @param string $controller
     * @param string $url
     */
    public function __construct($id, $controller, $url)
    {
        $this->id         = $id;
        $this->controller = $controller;
        $this->url        = $url;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @inheritDoc
     */
    public function getUrl()
    {
        return $this->url;
    }
}
