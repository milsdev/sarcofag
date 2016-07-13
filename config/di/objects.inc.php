<?php
return [
    'router' => DI\object(Slim\Router::class),
    'foundHandler' => DI\object(Slim\Handlers\Strategies\RequestResponse::class),
    Sarcofag\View\Helper\HelperManager::class =>
                DI\object()->method('addViewHelper',
                                        'screen', DI\get(\Sarcofag\View\Helper\ScreenSizeDetectionHelper::class))
                           ->method('addViewHelper',
                                        'wp', DI\get(\Sarcofag\View\Helper\WPHelper::class))
];
