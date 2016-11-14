<?php
namespace Sarcofag\SPI\EventManager\Handler;

interface HandlerInterface
{
    /**
     * @return void
     */
    public function __invoke();
}
