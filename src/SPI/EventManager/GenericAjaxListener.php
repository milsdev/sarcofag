<?php
namespace Sarcofag\SPI\EventManager;

use Sarcofag\SPI\EventManager\Handler\HandlerInterface;
use Sarcofag\SPI\EventManager\Strategy\CallableInterface;

class GenericAjaxListener implements ListenerInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var HandlerInterface
     */
    protected $handler;

    /**
     * Priority in the stack of events
     * inside Worpdress
     *
     * @var integer
     */
    protected $priority;

    /**
     * Indicates wether or not we should add hook to handle AJAX requests
     * for authenticated users.
     *
     * @var bool
     */
    protected $enableForAuthorizedUsers = true;

    /**
     * Indicates if we should add hook to handle AJAX requests on
     * the front-end for unauthenticated users, i.e. when
     * is_user_logged_in() returns false.
     *
     * @var bool
     */
    protected $enableForNotAuthorizedUsers = true;

    /**
     * Number of arguments which can receive
     * current listener.
     *
     * @var int
     */
    protected $argc;

    /**
     * GenericAjaxListener constructor.
     *
     * @param string | array $names
     * @param HandlerInterface $handler
     * @param bool $enableForAuthorizedUsers
     * @param bool $enableForNotAuthorizedUsers
     * @param null $priority
     * @param int $argc
     */
    public function __construct($names,
                                HandlerInterface $handler,
                                $enableForAuthorizedUsers = true,
                                $enableForNotAuthorizedUsers = true,
                                $priority = null,
                                $argc = 1)
    {
        $this->handler = $handler;
        $this->names = $names;
        $this->enableForAuthorizedUsers = $enableForAuthorizedUsers;
        $this->enableForNotAuthorizedUsers = $enableForNotAuthorizedUsers;
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
        return $this->handler;
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
        $names = [];

        foreach ((is_array($this->names) ? $this->names : [$this->names]) as $k=>$name) {
            if ($this->enableForNotAuthorizedUsers) {
                $names[] = 'wp_ajax_nopriv_'.$name;
            }

            if ($this->enableForAuthorizedUsers) {
                $names[] = 'wp_ajax_'.$name;
            }
        }

        return $names;
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
