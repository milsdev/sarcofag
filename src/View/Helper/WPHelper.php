<?php
namespace Sarcofag\View\Helper;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\Service\API\WP;


class WPHelper extends WP implements HelperInterface
{
    /**
     * @param array $arguments
     * @return bool | string
     */
    public function invoke(array $arguments)
    {
        return $this;
    }
}



