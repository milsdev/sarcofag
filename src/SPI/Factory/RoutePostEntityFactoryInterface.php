<?php
namespace Sarcofag\SPI\Factory;

use Sarcofag\Entity\RoutePostEntityInterface;

interface RoutePostEntityFactoryInterface
{
    /**
     * @param array $data
     *
     * @return RoutePostEntityInterface
     */
    public function create($data);
}
