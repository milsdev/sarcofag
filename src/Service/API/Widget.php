<?php
namespace Sarcofag\Service\Widget;

use Sarcofag\Service\API\Widget\AdminViewInterface;
use Sarcofag\Service\API\Widget\ThemeViewInterface;

class Widget extends \WP_Widget implements AdminViewInterface, ThemeViewInterface
{
    /**
     * Widget constructor.
     *
     * @param string $widgetId
     * @param string $widgetName
     * @param array $widgetOptions
     */
    public function __construct($widgetId, $widgetName, array $widgetOptions)
    {
        parent::__construct($widgetId, $widgetName, $widgetOptions);
    }
}
