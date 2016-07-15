<?php
namespace Sarcofag\Service\SPI\Filter;

class FilterEvent implements EventInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Callable
     */
    protected $callable;

    /**
     * @var integer
     */
    protected $priority;

    /**
     * @var array
     */
    protected $args;

    /**
     * FilterGeneric constructor.
     *
     * @param string $name
     * @param callable $callable
     * @param int $priority
     * @param array $args
     */
    public function __construct($name, Callable $callable, $priority = null, $args = [])
    {
        $this->callable = $callable;
        $this->name = $name;
        $this->priority = $priority;
        $this->args = $args;
    }

    /**
     * It is basic action to register in
     * wordpress.
     *
     * @param array $arguments
     *
     * @return void
     */
    public function __invoke($arguments)
    {
        return $this->callable($arguments);
    }

    /**
     * @return string[]
     */
    public function getNames()
    {
        return [$this->name];
    }

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
