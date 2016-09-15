<?php
namespace Sarcofag\SPI\Widget\Params;

use Sarcofag\View\Renderer\RendererInterface;

class GenericWidgetParams implements RenderableInterface
{
    /**
     * @var string | bool
     */
    protected $adminTemplate;

    /**
     * @var string
     */
    protected $themeTemplate;

    /**
     * @var string
     */
    protected $widgetName;

    /**
     * @var string
     */
    protected $widgetId;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * GenericWidgetParams constructor.
     *
     * @param string $id
     * @param string $name
     * @param string | bool $adminTemplate [OPTIONAL]
     * @param string $themeTemplate
     * @param RendererInterface $renderer
     * @param array $options
     */
    public function __construct($id,
                                $name,
                                $adminTemplate = false,
                                $themeTemplate,
                                RendererInterface $renderer,
                                $options = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->adminTemplate = $adminTemplate;
        $this->themeTemplate = $themeTemplate;
        $this->options = $options;
        $this->renderer = $renderer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string | bool
     */
    public function getAdminTemplate()
    {
        return $this->adminTemplate;
    }

    /**
     * @return string
     */
    public function getThemeTemplate()
    {
        return $this->themeTemplate;
    }

    /**
     * @return RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }
}
