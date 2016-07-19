<?php
namespace Sarcofag\Service\SPI\Widget\Params;

interface RenderableInterface extends BasicInterface
{
    /**
     * @return string
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
