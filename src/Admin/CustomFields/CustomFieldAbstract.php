<?php
namespace Sarcofag\Admin\CustomFields;

/**
 * Class CustomFieldAbstract
 *
 * @package Sarcofag\AdminExtensions\CustomFields
 */
abstract class CustomFieldAbstract
{
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


