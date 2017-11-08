<?php
namespace Sarcofag\Admin\CustomFields;

use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\EventManager\DataFilter\DataFilterInterface;
use Sarcofag\SPI\EventManager\ListenerInterface;

/**
 * Class CustomRoutePageMappingField
 * @package Sarcofag\AdminExtensions\CustomFields
 */
class CustomRoutePageMappingField extends CustomFieldAbstract implements ActionInterface, DataFilterInterface
{
    /**
     * Field name in html and identifier
     * in HTML tag of this field.
     *
     * @var string
     */
    protected $fieldName = 'route-mapped';

    /**
     * Name of the field which used to store
     * a value of it and as label in Admin Area
     *
     * @var string
     */
    protected $name = 'Custom Route';

    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners()
    {
        return [
            $this->factory->make('ActionListener',
                                   ['names' => $this->getNameOfHooksForColumnsContentToHandle(),
                                    'callable' => function ($column_name, $postId) {
                                            return $this->showColumnsContent($column_name, $postId);
                                    },
                                    'priority' => 10,
                                    'argc' => 2]),

            $this->factory->make('ActionListener',
                                   ['names' => 'admin_menu',
                                    'callable' => function () { return $this->createTextBox();}]),
            $this->factory->make('ActionListener',
                                   ['names' => 'save_post',
                                    'callable' => function ($post_id, \WP_Post $post = null) {
                                        return $this->saveTextBoxValue($post_id, $post);
                                     },
                                    'priority' => 10,
                                    'argc' => 2])
        ];
    }

    /**
     * @return ListenerInterface[]
     */
    public function getDataFilterListeners()
    {
        return [
            $this->factory->make('DataFilterListener',
                                    ['names' => $this->getNameOfHooksForColumnsHeadToHandle(),
                                     'callable' => function ($defaults) { return $this->showColumnsHead($defaults);},
                                     'priority' => 10]),
        ];
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
    protected function createTextBox()
    {
        $file = __FILE__;

        $render = \Closure::bind(function ($object, $box) use ($file) {
            echo $this->viewRenderer->render('admin/script/custom-fields/custom-route-page-mapping-field-box.phtml',
                                             ['object'=>$object,
                                              'box'=>$box,
                                              'file'=>$file,
                                              'name'=>$this->name,
                                              'fieldName'=>$this->fieldName]);
        }, $this);


        foreach ($this->postTypes as $postType) {
            add_meta_box( $this->fieldName.'-text', $this->name, $render, $postType, 'normal', 'high' );
        }
    }

    /**
     * Save the value of the field
     * to the concrete object identifier.
     *
     * @param number $postId
     * @param \WP_Post $post
     *
     * @return void
     */
    protected function saveTextBoxValue($postId, \WP_Post $post)
    {
        if (is_null($post)) return;
        
        if ( empty($_POST[$this->fieldName.'-nonce']) ||
                !wp_verify_nonce( $_POST[$this->fieldName.'-nonce'], plugin_basename( __FILE__ ) ) )
            return $postId;

        if ( !current_user_can( 'edit_post', $postId ) )
            return $postId;


        if (!metadata_exists('post', $postId, $this->name)) {
            $metaValue = false;
        } else {
            $metaValue = get_post_meta($postId, $this->name, true);
        }

        $newMetaValue = $_POST[$this->fieldName];
        
        if ($newMetaValue && false === $metaValue ) {
            add_post_meta($postId, $this->name, $newMetaValue, true);
        } elseif ( $newMetaValue != $metaValue ) {
            update_post_meta($postId, $this->name, $newMetaValue);
        } elseif ( '' == $newMetaValue && $metaValue ) {
            delete_post_meta($postId, $this->name, $metaValue);
        }
    }
}


