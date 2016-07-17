<?php
namespace Sarcofag\Service\SPI\Widget;

use Sarcofag\Service\API\WP\Widget as WPWidget;

interface WidgetInterface
{
    /**
     * Return the name of the widget
     *
     * @return string
     */
    public function getName();

    /**
     * @param WPWidget $wpWidget
     * @param array $placeholderParams Display arguments including 'before_title', 'after_title',
     *                                  'before_widget', and 'after_widget'.
     * @param array $settings The settings for the particular instance of the widget.
     *
     * @return string
     */
    public function render(WPWidget $wpWidget, array $placeholderParams = [], array $settings);
}
