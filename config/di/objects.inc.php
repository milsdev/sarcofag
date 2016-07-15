<?php
return [
    'router' => DI\object(Slim\Router::class),
    'foundHandler' => DI\object(Slim\Handlers\Strategies\RequestResponse::class),
    Sarcofag\View\Helper\HelperManager::class =>
                DI\object()->method('addViewHelper',
                                        'screen', \Sarcofag\View\Helper\ScreenSizeDetectionHelper::class)
                           ->method('addViewHelper',
                                        'wp', \Sarcofag\View\Helper\WPHelper::class)
                           ->method('addViewHelper',
                                        'layout', \Sarcofag\View\Helper\LayoutHelper::class)
                           ->method('addViewHelper',
                                        'include', \Sarcofag\View\Helper\IncludeHelper::class)
                           ->method('addViewHelper',
                                        'sidebar', \Sarcofag\View\Helper\SidebarHelper::class)

];
