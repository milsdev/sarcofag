<?php
namespace Sarcofag\SPI\Widget\Params;

interface RenderableInterface extends BasicInterface
{
    /**
     * @return string | bool
     */
    public function getAdminTemplate();

    /**
     * @return string
     */
    public function getThemeTemplate();

    /**
     * @return RendererInterface
     */
    public function getRenderer();
}
