<?php
namespace Sarcofag\Service\SPI\Filter;

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

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }
}
