<?php
namespace Sarcofag\SPI\EventManager\Handler;

use DI\FactoryInterface;
use Psr\Http\Message\MessageInterface;
use Sarcofag\API\WP;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class GenericAjaxHandler implements HandlerInterface
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var WP
     */
    protected $wpService;

    /**
     * GenericAjaxHandler constructor.
     *
     * @param callable $callable
     * @param FactoryInterface $factory
     * @param WP $wpService
     */
    public function __construct(Callable $callable,
                                FactoryInterface $factory,
                                WP $wpService)
    {
        $this->callable = $callable;
        $this->factory = $factory;
        $this->wpService = $wpService;
    }

    /**
     * This method will be called when WP will execute
     * ajax action handler, and will be stop whole PHP
     * process execution at the end of execution of this
     * method, because WP work in such a way.
     */
    public function __invoke()
    {
        $response = $this->factory->make(Response::class);
        $request = Request::createFromEnvironment(
            $this->factory
                 ->make(Environment::class, ['items' => ($_SERVER + $_REQUEST)]));

        $callable = $this->callable;

        $result = $callable($request, $response);
        $this->wpService->wp_send_json($result);
    }
}
