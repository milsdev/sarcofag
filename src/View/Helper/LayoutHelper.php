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
     * @param RendererInterface $renderer
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
        $script = '';
        $args = [];

        if (!empty($arguments[0])) {
            switch ($arguments[0]) {
                case 'header':
                    $script = 'layout/header.phtml';
                    break;
                case 'footer':
                    $script = 'layout/footer.phtml';
                    break;
                default:
                    $script = 'layout/'.$arguments[0].'.phtml';
                    break;
            }
        } else {
            throw new RuntimeException('First argument must be name of the layout, empty given .');
        }

        if (!empty($arguments[1]) && is_array($arguments[1])) {
            $args = $arguments[1];
        }
        
        return $this->renderer->render($script, $args);
    }
}



