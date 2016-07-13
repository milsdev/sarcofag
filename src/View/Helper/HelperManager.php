<?php
namespace Sarcofag\View\Helper;

class HelperManager
{
    /**
     * @var HelperInterface[]
     */
    protected $helpers;

    /**
     * @param string $helperName
     * @param HelperInterface $viewHelper
     *
     * @throws \Exception
     */
    public function addViewHelper($helperName, HelperInterface $viewHelper)
    {
        $this->helpers[$helperName] = $viewHelper;
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
    public function __get($name)
    {
        if (array_key_exists($name, $this->helpers)) {
            return $this->helpers[$name];
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
            return $this->helpers[$name]->invoke($arguments);
        } else {
            throw new \Exception("Could not found view helper for name ".$name);
        }
    }
}
