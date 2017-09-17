<?php
namespace Sarcofag\SPI\Factory;

use Sarcofag\Admin\CustomFields\CustomRoutePageMappingField;
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
     * @var CustomRoutePageMappingField
     */
    protected $customRoutePageMappingField;

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
     * @param CustomRoutePageMappingField $customRoutePageMappingField
     * @param WP $wpService
     * @param array $postTypeSettings
     */
    public function __construct(ControllerPageMappingField $controllerPageMappingField,
                                CustomRoutePageMappingField $customRoutePageMappingField,
                                WP $wpService,
                                array $postTypeSettings)
    {
        $this->controllerPageMappingField = $controllerPageMappingField;
        $this->customRoutePageMappingField = $customRoutePageMappingField;
        $this->wpService = $wpService;
        $this->postTypeSettings = $postTypeSettings;
    }

    /**
     * Transform data to the required
     * state to hydrate inside RoutePostEntityInterface
     * implementation and return to further usage.
     *
     * @param string $data Array with initial raw data.
     * @return RoutePostEntityInterface
     */
    public function create($data)
    {
        // Fetching controller to handle defined
        // post, controller defined inside the post
        // edit form in the field MappedController.
        $controller = $this->controllerPageMappingField
                           ->getValue($data['ID']);

        // Fetching custom route to handle defined
        // post, controller defined inside the post
        // edit form in the field CustomRoute.
        $customRoute = $this->customRoutePageMappingField
                            ->getValue($data['ID']);

        // Fetching default controller to be able to use
        // if no one controller were mentioned
        // while POST were created
        if (empty($controller) &&
            !empty($this->postTypeSettings[$data['post_type']]['defaultController'])) {
            $controller = $this->postTypeSettings[$data['post_type']]['defaultController'];
        }

        if (empty($customRoute)) {
            $url = parse_url($this->wpService->get_permalink($data['ID']), PHP_URL_PATH);
        } else {
            $url = $customRoute;
        }

        return new RoutePostEntity($data['ID'], $controller, $url);
    }
}
