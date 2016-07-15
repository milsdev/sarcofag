<?php
namespace Sarcofag\Service\SPI;

use Sarcofag\Service\SPI\Action\ActionInterface;

interface RegisterActionInterface
{
    /**
     * @param ActionInterface $action
     */
    public function attachAction(ActionInterface $action);

    /**
     * @param ActionInterface $action
     */
    public function registerActions();
}
