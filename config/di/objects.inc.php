<?php
return [
    'router' => DI\object(Slim\Router::class),
    'foundHandler' => DI\object(Slim\Handlers\Strategies\RequestResponse::class),
    Slim\App::class => DI\object()
                            ->constructor(DI\get(DI\Container::class)),

    'HelperManager' => DI\object(Sarcofag\View\Helper\HelperManager::class)
                           ->method('addViewHelper',
                                        'wp', \Sarcofag\View\Helper\WPHelper::class)
                           ->method('addViewHelper',
                                        'layout', \Sarcofag\View\Helper\LayoutHelper::class)
                           ->method('addViewHelper',
                                        'include', \Sarcofag\View\Helper\IncludeHelper::class)
                           ->method('addViewHelper',
                                        'sidebar', \Sarcofag\View\Helper\SidebarHelper::class),

    'ValidatorChain' => DI\object(\Zend\Validator\ValidatorChain::class)
                           ->method('setPluginManager',
                                    DI\object(\Zend\Validator\ValidatorPluginManager::class)
                                        ->constructor(DI\get(DI\Container::class)))
                           ->scope(\DI\Scope::PROTOTYPE),

    'InputFilterFactory' => DI\object(Zend\InputFilter\Factory::class)
                                            ->constructor(DI\object(Zend\InputFilter\InputFilterPluginManager::class)
                                                            ->constructor(DI\get(DI\Container::class)))
                                            ->method('setDefaultValidatorChain', DI\get('ValidatorChain'))
                            ->scope(\DI\Scope::PROTOTYPE)
];
