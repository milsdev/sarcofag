<?php
namespace Sarcofag\View\Helper;

use DI\FactoryInterface;
use Sarcofag\Exception\RuntimeException;

interface HelperManagerInterface
{
    /**
     * @param string $helperName
     * @param string $viewHelperClassName
     * @param array $helperArgs
     *
     * @throws \Exception
     */
    public function addViewHelper($helperName, $viewHelperClassName, $helperArgs = []);

    /**
     * @param string $helperName
     *
     * @return bool
     */
    public function helperExists($helperName);

    /**
     * @param string $name
     *
     * @return HelperInterface
     * @throws \Exception
     */
    public function __get($name);

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, array $arguments);
}
