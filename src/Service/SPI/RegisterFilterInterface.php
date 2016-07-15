<?php
namespace Sarcofag\Service\SPI;

use Sarcofag\Service\SPI\Filter\FilterInterface;

interface RegisterFilterInterface
{
    /**
     * @param FilterInterface $filter
     */
    public function attachFilter(FilterInterface $filter);

    /**
     * Register all attached filters
     */
    public function registerFilters();
}
