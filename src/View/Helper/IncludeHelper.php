<?php
namespace Sarcofag\View\Helper;

use DI\FactoryInterface;
use Sarcofag\Exception\RuntimeException;
use Sarcofag\View\Renderer\RendererInterface;

class IncludeHelper implements HelperInterface
{
    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * AbstractPartialHelper constructor.
     *
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param array $arguments
     *
     * @return bool | string
     */
    public function invoke(array $arguments)
    {
        if (count($arguments) < 1) {
            throw new RuntimeException("You have to define a path to the template");
        }

        if (array_key_exists(1, $arguments) && is_array($arguments[1])) {
            return $this->renderer->render($arguments[0], $arguments[1]);
        } else {
            return $this->renderer->render($arguments[0]);
        }
    }
}
