<?php
namespace Sarcofag\SPI\Factory;

use Sarcofag\API\WP;
use Sarcofag\Entity\RoutePostEntity;
use Sarcofag\Entity\RoutePostEntityInterface;
use Sarcofag\Admin\CustomFields\ControllerPageMappingField;

/**
 * Class RoutePostEntityFactory
 *
 * Factory to create RoutePostEntityInterface
 * objects, and populate it with initial data,
 * which will be minimum required for creating
 * Route and register it in the App and Router.
 *
 * @package Sarcofag\Factory
 */
class RoutePostEntityFactory implements RoutePostEntityFactoryInterface
{
    /**
     * @var ControllerPageMappingField
     */
    protected $controllerPageMappingField;

    /**
     * @var array
     */
    protected $postTypeSettings;

    /**
     * @var WP
     */
    protected $wpService;

    /**
     * RoutePostEntityFactory constructor.
     *
     * @param ControllerPageMappingField $controllerPageMappingField
     * @param WP $wpService
     * @param array $postTypeSettings
     */
    public function __construct(ControllerPageMappingField $controllerPageMappingField,
                                WP $wpService,
                                array $postTypeSettings)
    {
        $this->controllerPageMappingField = $controllerPageMappingField;
        $this->wpService = $wpService;
        $this->postTypeSettings = $postTypeSettings;
    }

    /**
     * Transform data to the required
     * state to hydrate inside RoutePostEntityInterface
     * implementation and return to further usage.
     *
     * @param array $data Array with initial raw data.
     * @return RoutePostEntityInterface
     */
    public function create($data)
    {
        // Fetching controller to handle defined
        // post, controller defined inside the post
        // edit form in the field MappedController.
        $controller = $this->controllerPageMappingField
                           ->getValue($data['ID']);

        // Fetching default controller to be able to use
        // if no one controller were mentioned
        // while POST were created
        if (empty($controller) &&
            !empty($this->postTypeSettings[$data['post_type']]['defaultController'])) {
            $controller = $this->postTypeSettings[$data['post_type']]['defaultController'];
        }

        $url = parse_url($this->wpService->get_permalink($data['ID']), PHP_URL_PATH);

        return new RoutePostEntity($data['ID'], $controller, $url);
    }
}
