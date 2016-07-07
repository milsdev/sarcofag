<?php
/*  Copyright Mil's (http://milsdev.com/)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
namespace Sarcofag\AdminExtensions\CustomFields;

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

    /**
     * @return void
     */
    abstract protected function createSelectBox();

    /**
     * Save the value of the field
     * to the concrete object identifier.
     *
     * @param number $post_id
     * @param \WP_Post $post
     *
     * @return mixed
     */
    abstract protected function saveSelectBoxValue($post_id, \WP_Post $post);
}


