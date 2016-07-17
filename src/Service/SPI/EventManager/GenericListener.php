<?php
namespace Sarcofag\Service\SPI\EventManager;

class GenericListener implements ListenerInterface
{
    /**
     * @var string | string[]
     */
    protected $names;

    /**
     * @var \Callable
     */
    protected $callable;

    /**
     * Priority in the stack of events
     * inside Worpdress
     *
     * @var integer
     */
    protected $priority;

    /**
     * Number of arguments which can receive
     * current listener.
     *
     * @var int
     */
    protected $argc;

    /**
     * FilterGeneric constructor.
     *
     * @param string | string[] $names
     * @param callable $callable
     * @param int $priority [OPTIONAL]
     * @param int $argc [OPTIONAL]
     */
    public function __construct($names, Callable $callable, $priority = null, $argc = 1)
    {
        $this->callable = $callable;
        $this->names = $names;
        $this->priority = $priority;
        $this->argc = $argc;
    }

    /**
     * It is basic action to register in
     * wordpress.
     *
     * @return Callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * It is basic action to register in
     * wordpress.
     *
     * @param array $arguments [OPTIONAL]
     *
     * @return void
     */
    public function __invoke($arguments = [])
    {
        $callable = $this->callable;
        $callable($arguments);
    }

    /**
     * @return string[]
     */
    public function getNames()
    {
        if (is_array($this->names)) {
            return $this->names;
        } else {
            return [$this->names];
        }
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return int
     */
    public function getArgc()
    {
        return $this->argc;
    }
}
