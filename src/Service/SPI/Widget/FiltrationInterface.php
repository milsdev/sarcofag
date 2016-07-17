<?php
namespace Sarcofag\Service\SPI\Widget;

use Sarcofag\Service\API\WP\Widget;

interface FiltrationInterface
{
    /**
     * Method return filtered settings
     * to persist for current WP Widget.
     *
     * @param Widget $wpWidget
     * @param array $oldSettings
     * @param array $newSettings
     *
     * @return array Return filtered settings
     */
    public function filter(Widget $wpWidget, $oldSettings, $newSettings);
}
