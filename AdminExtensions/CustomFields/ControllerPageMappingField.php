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

require_once __DIR__ . "/CustomFieldAbstract.php";

/**
 * Class ControllerPageMappingField
 * @package Sarcofag\AdminExtensions\CustomFields
 */
class ControllerPageMappingField extends CustomFieldAbstract
{
    /**
     * Field name in html and identifier
     * in HTML tag of this field.
     *
     * @var string
     */
    protected $fieldName = 'mapped-controller';

    /**
     * Name of the field which used to store
     * a value of it and as label in Admin Area
     *
     * @var string
     */
    protected $name = 'Mapped Controller';

    /**
     * ControllerPageMappingField constructor.
     */
    public function register()
    {
        add_filter('manage_page_posts_columns', function ($defaults) { return $this->showColumnsHead($defaults);}, 10);
        add_action('manage_page_posts_custom_column', function ($column_name, $post_ID) {
            return $this->showColumnsContent($column_name, $post_ID);
        }, 10, 2);
        add_filter('manage_post_posts_columns', function ($defaults) { return $this->showColumnsHead($defaults);}, 10);
        add_action('manage_post_posts_custom_column', function ($column_name, $post_ID) {
            return $this->showColumnsContent($column_name, $post_ID);
        }, 10, 2);
        add_action( 'admin_menu', function () { return $this->createSelectBox();});
        add_action( 'save_post', function ($post_id, \WP_Post $post) {
            return $this->saveSelectBoxValue($post_id, $post);
        }, 10, 2 );
    }

    /**
     * @param number $postId
     *
     * @return string | false
     */
    public function getValue($postId)
    {

        $value = get_post_meta( $postId, $this->name, true );
        if (empty($value)) {
            return false;
        }

        return $value;
    }

    /**
     * Show on admin page Pages in the grid of pages
     * content of column with name defined in $this->name attribute
     *
     * @param string $column_name
     * @param number $post_ID
     */
    protected function showColumnsContent($column_name, $postId)
    {
        if ($column_name == 'controller_name') {
            echo get_post_meta( $postId, $this->name, true );
        }
    }

    /**
     * Show on admin page Pages in the grid of pages
     * column with label defined in $this->name attribute
     *
     * @param array $defaults
     * @return array
     */
    protected function showColumnsHead($defaults)
    {
        $defaults['controller_name'] = $this->name;
        return $defaults;
    }

    /**
     * @return void
     */
    protected function createSelectBox()
    {
        $file = __FILE__;

        $render = \Closure::bind(function ($object, $box) use ($file) {
            include __DIR__ . '/templates/ControllerPageMappingFieldBox.phtml';
        }, $this);

        add_meta_box( $this->fieldName.'-select', $this->name, $render, 'page', 'normal', 'high' );
        add_meta_box( $this->fieldName.'-select', $this->name, $render, 'post', 'normal', 'high' );
    }

    /**
     * Save the value of the field
     * to the concrete object identifier.
     *
     * @param number $postId
     * @param \WP_Post $post
     *
     * @return mixed
     */
    protected function saveSelectBoxValue($postId, \WP_Post $post)
    {

        if ( empty($_POST[$this->fieldName.'-nonce']) ||
                !wp_verify_nonce( $_POST[$this->fieldName.'-nonce'], plugin_basename( __FILE__ ) ) )
            return $postId;

        if ( !current_user_can( 'edit_post', $postId ) )
            return $postId;

        $metaValue = get_post_meta( $postId, $this->name, true );
        $newMetaValue = $_POST[$this->fieldName];

        if ( $newMetaValue && '' == $metaValue )
            add_post_meta( $postId, $this->name, $newMetaValue, true );

        elseif ( $newMetaValue != $metaValue )
            update_post_meta( $postId, $this->name, $newMetaValue );

        elseif ( '' == $newMetaValue && $metaValue )
            delete_post_meta( $postId, $this->name, $metaValue );
    }

    /**
     * @return array
     */
    protected function readAllControllersFromFS()
    {
        $objects = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(get_template_directory() . '/src/api/Controller'),
                                            \RecursiveIteratorIterator::SELF_FIRST);
        
        foreach($objects as $name => $object){ /* @var $object \SplFileInfo */
            if ($object->isDir()) continue;
            $name = str_replace(get_template_directory() . '/src/api/Controller/', '', $name);
            $name = str_replace('/', '\\', $name);
            $name = str_replace('.php', '', $name);
            $controllers[] = "Api\\Controller\\".$name;
        }

        return $controllers;
    }
}


