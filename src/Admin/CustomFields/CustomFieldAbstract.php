<?php
namespace Sarcofag\Admin\CustomFields;

use DI\FactoryInterface;
use Sarcofag\View\Renderer\RendererInterface;

/**
 * Class CustomFieldAbstract
 *
 * @package Sarcofag\AdminExtensions\CustomFields
 */
abstract class CustomFieldAbstract
{
    /**
     * @var RendererInterface
     */
    protected $viewRenderer;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * Array of post types which are existed in the system
     * and might have it is own routes and handlers.
     *
     * @var array
     */
    protected $postTypes;

    /**
     * CustomFieldAbstract constructor.
     *
     * @param RendererInterface $viewRenderer
     * @param FactoryInterface $factory
     * @param array $postTypeSettings
     */
    public function __construct(RendererInterface $viewRenderer, FactoryInterface $factory, array $postTypeSettings)
    {
        $this->viewRenderer = $viewRenderer;
        $this->factory = $factory;
        $this->postTypes = array_keys($postTypeSettings);
    }

    /**
     * @return array
     */
    protected function getNameOfHooksForColumnsContentToHandle()
    {
        return array_map(function ($name) { return "manage_{$name}_posts_custom_column"; }, $this->postTypes);
    }

    /**
     * @return array
     */
    protected function getNameOfHooksForColumnsHeadToHandle()
    {
        return array_map(function ($name) { return "manage_{$name}_posts_columns"; }, $this->postTypes);
    }

    /**
     * Show on admin page Pages in the grid of pages
     * content of column with name Slim Controller
     *
     * @param string $column_name
     * @param number $post_ID
     */
    abstract protected function showColumnsContent($column_name, $post_ID);

    /**
     * Show on admin page Pages in the grid of pages
     * column with label Slim Controller
     *
     * @param array $defaults
     * @return array
     */
    abstract protected function showColumnsHead($defaults);
}


