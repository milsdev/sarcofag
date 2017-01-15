<?php
namespace Sarcofag\SPI\Routing;


use Sarcofag\Entity\RoutePostEntityInterface;
use Sarcofag\Exception\InvalidArgumentException;

/**
 * Class RoutePostFilterAggregate
 *
 * Aggregator class using when need
 * to pass few filters to filter
 * given route.
 *
 * @package Sarcofag\SPI\Routing
 */
class RoutePostFilterAggregate implements RoutePostFilterInterface
{
    /**
     * @var RoutePostFilterInterface[]
     */
    protected $filters;

    /**
     * RoutePostFilterAggregate constructor.
     */
    public function __construct()
    {
        $this->filters = new \SplObjectStorage();
    }

    /**
     * @param RoutePostFilterInterface $postFilter
     * @return RoutePostFilterAggregate
     */
    public function addFilter(RoutePostFilterInterface $postFilter)
    {
        $this->filters->attach($postFilter);
        return $this;
    }

    /**
     * Filtering method to decide if RoutePostEntity
     * implementation correspond to the filter
     * rules. This is aggregate so it is will do
     * a filtration thru the full list of $filters
     * contained inside this object.
     *
     * @param RoutePostEntityInterface | object $routePostEntity
     * @throw InvalidArgumentException If $routePostEntity has unrecognized type
     * @return bool
     */
    public function filter($routePostEntity)
    {
        if (!$routePostEntity instanceof RoutePostEntityInterface) {
            throw new InvalidArgumentException("Unexpected type for routePostEntity");
        }

        foreach ($this->filters as $filter) {
            if (!$filter->filter($routePostEntity)) {
                return false;
            }
        }


        return true;
    }
}
