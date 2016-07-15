<?php
namespace Sarcofag\Service\SPI\Action;

interface EventInterface
{
    /**
     * It is basic event to execute in
     * wordpress context, like an action.
     *
     * @param array $arguments
     * 
     * @return void
     */
    public function __invoke($arguments);

    /**
     * @return string[]
     */
    public function getNames();
}
