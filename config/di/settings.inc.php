<?php
return [
    'template.paths' => ['admin' => __DIR__ . '/../../src/admin/view',
                         'theme' => __DIR__ . '/../../src/theme/view'],
    'ui.js.path.mapping' => [],
    'autoloader.paths' => [],
    'settings' => [
        'outputBuffering'=>'append',
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'db' => [
            'user' => DB_USER,
            'password' => DB_PASSWORD,
            'dbname' => DB_NAME,
            'host' => DB_HOST
        ]
    ]
];
