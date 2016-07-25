<?php
namespace Sarcofag\SPI\Widget;

use Sarcofag\API\WP\Widget;

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
