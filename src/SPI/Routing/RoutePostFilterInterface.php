<?php

namespace Sarcofag\SPI\Routing;

use Sarcofag\Entity\RoutePostEntityInterface;

interface RoutePostFilterInterface
{
    /**
     * Filter to decide if $routePostEntity
     * correspond to the filter rules.
     *
     * @param RoutePostEntityInterface | object $routePostEntity
     * @throw InvalidArgumentException If $routePostEntity has unrecognized type
     * @return bool
     */
    public function filter($routePostEntity);
}
