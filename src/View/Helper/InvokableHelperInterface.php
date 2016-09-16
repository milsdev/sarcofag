<?php
namespace Sarcofag\View\Helper;

interface InvokableHelperInterface
{
    /**
     * @param array $arguments
     * @return bool | string
     */
    public function invoke(array $arguments);
}
