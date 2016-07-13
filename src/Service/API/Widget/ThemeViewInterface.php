<?php
namespace Sarcofag\Service\API\Widget;

use Psr\Http\Message\ResponseInterface;

interface ThemeViewInterface
{
    /**
     * Return rendered content
     * for displaying in placeholder
     * at the THEME.
     *
     * @return string
     */
    public function getThemeView();
}
