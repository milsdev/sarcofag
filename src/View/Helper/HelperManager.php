<?php
namespace Sarcofag\View\Helper;

use DI\FactoryInterface;
use Sarcofag\Exception\RuntimeException;

class HelperManager implements HelperManagerInterface
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
     * @param array $helperArgs [OPTIONAL]
     *
     * @throws \Exception
     */
    public function addViewHelper($helperName, $viewHelperClassName, $helperArgs = [])
    {
        $this->helpers[$helperName] = [$viewHelperClassName, $helperArgs];
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
     * @throws \Exception
     */
    protected function getHelper($name)
    {
        if (array_key_exists($name, $this->helpers)) {
            if (!is_object($this->helpers[$name])) {
                $this->helpers[$name] = $this->factory->make($this->helpers[$name][0], $this->helpers[$name][1]);
            }

            return $this->helpers[ $name ];
        } else {
            throw new RuntimeException("Could not found view helper for name ".$name);
        }
    }

    /**
     * @param string $name
     *
     * @return HelperInterface
     * @throws RuntimeException
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->helpers)) {
            return $this->getHelper($name);
        } else {
            throw new RuntimeException("Could not found view helper instance for name ".$name);
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     * @throws RuntimeException
     */
    public function __call($name, array $arguments)
    {
        if (array_key_exists($name, $this->helpers)) {
            if (!$this->getHelper($name) instanceof InvokableHelperInterface) {
                throw new RuntimeException("Called helper do not implement InvokableHelperInterface");
            }

            return $this->getHelper($name)->invoke($arguments);
        } else {
            throw new RuntimeException("Could not found view helper for name ".$name);
        }
    }
}
