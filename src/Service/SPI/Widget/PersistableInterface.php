<?php
namespace Sarcofag\Service\SPI\Widget;

use Sarcofag\Service\API\WP\Widget;

interface PersistableInterface extends FiltrationInterface
{
    /**
     * Method return rendered HTML
     * for the form displayed in the
     * Admin WP Area.
     *
     * @param array $settings
     *
     * @return mixed
     */
    public function renderForm($settings);
}
