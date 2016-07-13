<?php
namespace Sarcofag\View\Helper;

use Sarcofag\Exception\RuntimeException;
use Sarcofag\View\Renderer\SimpleRenderer;
use Slim\Http\Response;

class LayoutHelper implements HelperInterface
{
    /**
     * @var SimpleRenderer
     */
    protected $renderer;

    /**
     * LayoutHelper constructor.
     *
     * @param SimpleRenderer $simpleViewRenderer
     */
    public function __construct(SimpleRenderer $simpleViewRenderer)
    {
        $this->renderer = $simpleViewRenderer;
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



