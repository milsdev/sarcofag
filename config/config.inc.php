<?php
return [
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
];
