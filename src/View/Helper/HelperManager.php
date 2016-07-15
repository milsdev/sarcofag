<?php
namespace Sarcofag\View\Helper;

use DI\FactoryInterface;
use Sarcofag\Exception\RuntimeException;

class HelperManager
{
    /**
     * @var HelperInterface[]
     */
    protected $helpers;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * HelperManager constructor.
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param string $helperName
     * @param string $viewHelperClassName
     *
     * @throws \Exception
     */
    public function addViewHelper($helperName, $viewHelperClassName)
    {
        if (!class_exists($viewHelperClassName) ||
                !in_array(HelperInterface::class, class_implements($viewHelperClassName))) {
            throw new RuntimeException("Incorrect helper class name or Helper do not implement HelperInterface");
        }
        
        $this->helpers[$helperName] = $viewHelperClassName;
    }

    /**
     * @param string $helperName
     *
     * @return bool
     */
    public function helperExists($helperName)
    {
        return array_key_exists($helperName, $this->helpers);
    }

    /**
     * @param string $name
     *
     * @return HelperInterface
     */
    protected function getHelper($name)
    {
        if (array_key_exists($name, $this->helpers)) {
            if (!is_object($this->helpers[$name])) {
                $this->helpers[$name] = $this->factory->make($this->helpers[$name]);
            }

            return $this->helpers[ $name ];
        } else {
            throw new \Exception("Could not found view helper for name ".$name);
        }
    }

    /**
     * @param string $name
     *
     * @return HelperInterface
     * @throws \Exception
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->helpers)) {
            return $this->getHelper($name);
        } else {
            throw new \Exception("Could not found view helper instance for name ".$name);
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, array $arguments)
    {
        if (array_key_exists($name, $this->helpers)) {
            return $this->getHelper($name)->invoke($arguments);
        } else {
            throw new \Exception("Could not found view helper for name ".$name);
        }
    }
}
