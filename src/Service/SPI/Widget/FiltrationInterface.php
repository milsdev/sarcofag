<?php
namespace Sarcofag\Service\SPI\Widget;

use Sarcofag\Service\API\WP\Widget;

interface FiltrationInterface
{
    /**
     * Method return filtered settings
     * to persist for current WP Widget.
     *
     * @param array $newSettings
     * @param array $oldSettings
     *
     * @return array Return filtered settings
     */
    public function filter($newSettings, $oldSettings);
}
