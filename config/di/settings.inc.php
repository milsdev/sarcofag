<?php
return [
    'zend.servicemanager.settings' => [
        'abstract_factories' => [\Sarcofag\API\Zend\AbstractFactory::class]
    ],

    'template.paths' => ['admin' => __DIR__ . '/../../src/Admin/view',
                         'theme' => __DIR__ . '/../../src/Theme/view'],
    'ui.js.path.mapping' => [],
    'page.notfound' => 'theme/script/notfound.phtml',
    'page.notallowed' => 'theme/script/notallowed.phtml',
    'page.error' => 'theme/script/error.phtml',
    'autoloader.paths' => [],
    'settings' => [
        'outputBuffering'=>'append',
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
//        'cache' => [
//            'adapter' => [
//                'name'    => \Zend\Cache\Storage\Adapter\Memcached::class,
//                'options' => ['ttl' => 3600,
//                              'servers' => [['127.0.0.1', '11211']],
//                              'liboptions' => [
//                                  'COMPRESSION' => true,
//                                  'binary_protocol' => true,
//                                  'no_block' => true,
//                                  'connect_timeout' => 100
//                              ]]
//            ],
//            'plugins' =>[
//                'exception_handler' => ['throw_exceptions' => false],
//            ]
//        ],
        'db' => [
            'user' => DB_USER,
            'password' => DB_PASSWORD,
            'dbname' => DB_NAME,
            'host' => DB_HOST
        ]
    ],
    'postTypes' => [
        'post' => ['defaultController' => 'DefaultStaticPostController'],
        'page' => ['defaultController' => 'DefaultStaticPageController']
    ],
];
