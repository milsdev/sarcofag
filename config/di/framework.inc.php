<?php

return [
    'router' => DI\object(Slim\Router::class),
    'foundHandler' => DI\object(Slim\Handlers\Strategies\RequestResponse::class),
    'errorHandler' => function (\Interop\Container\ContainerInterface $container) {
        if (WP_DEBUG) {
            return new \Slim\Handlers\Error($container->get('settings')['displayErrorDetails']);
        } else {
            return $container->get('ErrorController');
        }
    },

    'notFoundHandler' => DI\get('NotFoundController'),
    'notAllowedHandler' => DI\get('NotAllowedController'),
    'environment' => DI\object(\Slim\Http\Environment::class)->constructorParameter('items', $_SERVER),

    'request' => function (\Interop\Container\ContainerInterface $container) {
        return \Slim\Http\Request::createFromEnvironment($container->get('environment'));
    },

    'response' => function (\Interop\Container\ContainerInterface $container) {
        $headers = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
        $response = new \Slim\Http\Response(200, $headers);

        return $response->withProtocolVersion($container->get('settings')['httpVersion']);
    },

    'callableResolver' => DI\get(\Slim\CallableResolver::class)
];
