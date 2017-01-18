<?php
return [
    'HelperManager' => DI\object(Sarcofag\View\Helper\HelperManager::class)
                           ->method('addViewHelper',
                                        'wp', \Sarcofag\View\Helper\WPHelper::class)
                           ->method('addViewHelper',
                                        'layout', \Sarcofag\View\Helper\LayoutHelper::class)
                           ->method('addViewHelper',
                                        'include', \Sarcofag\View\Helper\IncludeHelper::class)
                           ->method('addViewHelper',
                                        'sidebar', \Sarcofag\View\Helper\SidebarHelper::class),

    'RoutePostEntityFactory' => DI\object(\Sarcofag\SPI\Factory\RoutePostEntityFactory::class),
    'RoutePostEntityFilter' => DI\get(\Sarcofag\SPI\Routing\RoutePostFilterAggregate::class),

    Slim\App::class => DI\object()->constructor(DI\get(DI\Container::class)),
    Sarcofag\App::class => DI\object()->constructorParameter('settings', DI\get('settings'))
                                      ->constructorParameter('routePostEntityFactory',
                                                              DI\get('RoutePostEntityFactory'))
                                      ->constructorParameter('routePostEntityFilter',
                                                              DI\get('RoutePostEntityFilter'))
                                      ->method('setCache', DI\get('DefaultCacheStorage')),

    \Sarcofag\Cache\CacheHandler::class => DI\object()->constructorParameter('routePostEntityFactory',
                                                                             DI\get('RoutePostEntityFactory')),

    'ValidatorChain' => DI\object(\Zend\Validator\ValidatorChain::class)
                           ->method('setPluginManager',
                                    DI\object(\Zend\Validator\ValidatorPluginManager::class)
                                        ->constructor(DI\get(DI\Container::class),
                                                      DI\get('zend.servicemanager.settings')))
                           ->scope(\DI\Scope::PROTOTYPE),

    'InputFilterFactory' => DI\object(Zend\InputFilter\Factory::class)
                                            ->constructor(DI\object(Zend\InputFilter\InputFilterPluginManager::class)
                                                            ->constructor(DI\get(DI\Container::class),
                                                                          DI\get('zend.servicemanager.settings')))
                                            ->method('setDefaultValidatorChain', DI\get('ValidatorChain'))
                            ->scope(\DI\Scope::PROTOTYPE),

    'NotFoundController' => DI\object(Sarcofag\Theme\Controller\SimpleRendererController::class)
        ->constructorParameter('templateToRender', DI\get('page.notfound')),
    'ErrorController' => DI\object(Sarcofag\Theme\Controller\SimpleRendererController::class)
        ->constructorParameter('templateToRender', DI\get('page.error')),
    'NotAllowedController' => DI\object(Sarcofag\Theme\Controller\SimpleRendererController::class)
        ->constructorParameter('templateToRender', DI\get('page.notallowed')),

    \Sarcofag\SPI\Factory\RoutePostEntityFactory::class =>
        DI\object()->constructorParameter('postTypeSettings', DI\get('postTypes'))
];
