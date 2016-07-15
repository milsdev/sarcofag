<?php
namespace Sarcofag\View\Helper;

interface HelperInterface
{
    /**
     * @param array $arguments
     * @return bool | string
     */
    public function invoke(array $arguments);
}
