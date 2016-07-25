<?php
return [
    'router' => DI\object(Slim\Router::class),
    'foundHandler' => DI\object(Slim\Handlers\Strategies\RequestResponse::class),
    Slim\App::class => DI\object()
                            ->constructor(DI\get(DI\Container::class)),

    'HelperManager' => DI\object(Sarcofag\View\Helper\HelperManager::class)
                           ->method('addViewHelper',
                                        'screen', \Sarcofag\View\Helper\ScreenSizeDetectionHelper::class)
                           ->method('addViewHelper',
                                        'wp', \Sarcofag\View\Helper\WPHelper::class)
                           ->method('addViewHelper',
                                        'layout', \Sarcofag\View\Helper\LayoutHelper::class)
                           ->method('addViewHelper',
                                        'include', \Sarcofag\View\Helper\IncludeHelper::class)
                           ->method('addViewHelper',
                                        'sidebar', \Sarcofag\View\Helper\SidebarHelper::class)
                           ->method('addViewHelper',
                                        'includeUIComponent',
                                        \Sarcofag\View\Helper\UIComponentHelper::class,
                                        ['uiComponentPaths' => DI\get('ui.js.paths')])
];
