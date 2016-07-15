<?php
namespace Sarcofag\Service\SPI\Action;

class ActionEvent implements EventInterface
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
     * ActionGeneric constructor.
     *
     * @param string $name
     * @param callable $callable
     */
    public function __construct($name, Callable $callable)
    {
        $this->callable = $callable;
        $this->name = $name;
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
        $closure = $this->callable;
        $closure($arguments);
    }

    /**
     * @return string[]
     */
    public function getNames()
    {
        return [$this->name];
    }
}
