<?php
namespace Sarcofag\Admin\CustomFields;
use Sarcofag\View\Renderer\RendererInterface;

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
     * @var RendererInterface
     */
    protected $viewRenderer = null;

    /**
     * ControllerPageMappingField constructor.
     */
    public function __construct(RendererInterface $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }

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
            echo $this->viewRenderer->render('admin/script/custom-fields/controller-page-mapping-field-box.phtml',
                                             ['object'=>$object,
                                              'box'=>$box,
                                              'file'=>$file,
                                              'name'=>$this->name,
                                              'readAllControllersFromFS' =>
                                                  \Closure::bind(function () {
                                                      return $this->readAllControllersFromFS();
                                                  }, $this),
                                              'fieldName'=>$this->fieldName]);
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


