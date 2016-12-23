<?php
namespace Sarcofag;

use DI;
use Sarcofag\API\WP;
use Sarcofag\SPI\Routing\PostFilterInterface;
use Slim;
use Sarcofag\Admin\CustomFields\ControllerPageMappingField;
use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\EventManager\ListenerInterface;

class App implements ActionInterface
{
    /**
     * @var DI\FactoryInterface
     */
    protected $factory;

    /**
     * @var Slim\App
     */
    protected $app;

    /**
     * @var WP
     */
    protected $wpService;

    /**
     * @var PostFilterInterface
     */
    protected $postFilter;

    /**
     * App constructor.
     *
     * @param DI\FactoryInterface $factory
     * @param Slim\App $slimApp
     * @param WP $wpService
     * @param PostFilterInterface $postFilter
     */
    public function __construct(DI\FactoryInterface $factory,
                                Slim\App $slimApp,
                                WP $wpService,
                                PostFilterInterface $postFilter)
    {
        $this->factory = $factory;
        $this->app = $slimApp;
        $this->wpService = $wpService;
        $this->postFilter = $postFilter;
    }

    /**
     * @return ListenerInterface[]
     */
    public function getActionListeners()
    {
        $routeDispatcher = function () {
            $postTypeSettings = $this->app->getContainer()->get('postTypes');

            foreach ($postTypeSettings as $postType => $settings) {
                $this->setRouteForPostTypes($postType, $settings['defaultController']);
            }

            $this->app->run();
        };

        return [
            $this->factory->make('ActionListener', [
                'names' => 'template_include',
                'callable' => $routeDispatcher,
                'priority' => 99
            ])
        ];
    }

    protected function setRouteForPostTypes($postType, $defaultController)
    {
        $controllerPageMapping = $this->app->getContainer()->get(ControllerPageMappingField::class);

        /**
         * @FIXME: In future versions, need to change
         * adding routes to the map of get|post. All WP PAGES and POSTS must
         * coresponds to only GET method. Because it is has content only for reading.
         * All other logic like writing or another logic should be implemented in WIDGETS
         * or in controllers via declarring new routes and handlers for them.
         */
        foreach ($this->wpService->get_posts(['numberposts' => -1, 'post_type' => $postType]) as $post) {
            $controller = $controllerPageMapping->getValue($post->ID);

            if (! $this->postFilter->filter($post)) continue;

            $this->app->map(['get', 'post'], parse_url(get_permalink($post), PHP_URL_PATH),
                $this->app->getContainer()
                    ->get(empty($controller) ? $defaultController : $controller))
                ->setArgument('requestedEntity', $post);
        }
    }
}
