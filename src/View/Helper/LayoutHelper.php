<?php
namespace Sarcofag\View\Helper;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\View\Renderer\RendererInterface;
use Slim\Http\Response;

class LayoutHelper implements HelperInterface
{
    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * LayoutHelper constructor.
     *
     * @param RendererInterface $simpleViewRenderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function invoke(array $arguments)
    {
        if (!empty($arguments[0])) {
            switch ($arguments[0]) {
                case 'header':
                    return $this->renderer->render('layout/header.phtml');
                case 'footer':
                    return $this->renderer->render('layout/footer.phtml');
            }
        } else {
            return $this->renderer->render('layout/'.$arguments[0].'.phtml');
        }

        return $this->renderer->render('layout/header.phtml');
    }
}



