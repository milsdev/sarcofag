<?php
namespace Sarcofag\View\Helper;

use DI\FactoryInterface;
use Sarcofag\Exception\RuntimeException;

class SidebarHelper implements HelperInterface
{
    /**
     * @var HelperManager
     */
    protected $helperManager;

    /**
     * DisplaySidebarHelper constructor.
     *
     * @param FactoryInterface $factory
     */
    public function __construct(HelperManager $helperManager)
    {
        $this->helperManager = $helperManager;
    }

    /**
     * @param array $arguments
     * @return bool | string
     */
    public function invoke(array $arguments)
    {
        if (count($arguments) < 1) {
            throw new RuntimeException("You have to define a name of the placeholder to display");
        }

        if ($this->helperManager->wp->is_active_sidebar($arguments[0])) {
            if (count($arguments) == 2) {
                return $this->helperManager->include($arguments[1], ['sidebar' => $arguments[0]]);
            } else {
                ob_start();
                $this->helperManager->wp->dynamic_sidebar($arguments[0]);
                return ob_get_clean();
            }
        }

        return '';
    }
}
