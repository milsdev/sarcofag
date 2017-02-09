<?php
namespace Sarcofag\SPI\EventManager;

interface ListenerInterface
{
    /**
     * It is basic event to execute in
     * wordpress context.
     *
     * @return Callable
     */
    public function getCallable();

    /**
     * @return string[]
     */
    public function getNames();

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @return int
     */
    public function getArgc();
}
