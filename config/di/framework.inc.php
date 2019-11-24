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
    'environment' => function (\Interop\Container\ContainerInterface $container) {
        $server = $_SERVER;

        if ($container->has('icl.active.languages')) {
            if (preg_match('/^\/([a-z]{2})/', $server['REQUEST_URI'], $matches)) {
                if (in_array(trim($matches[1]), $container->get('icl.active.languages'))) {
                    $server['REQUEST_URI'] =
                        preg_replace("/^(\/{$matches[1]})/", '', $server['REQUEST_URI']);
                }
            }
        }

        return new \Slim\Http\Environment($server);
    },

    'request' => function (\Interop\Container\ContainerInterface $container) {
        return \Slim\Http\Request::createFromEnvironment($container->get('environment'));
    },

    'response' => function (\Interop\Container\ContainerInterface $container) {
        $headers = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
        $response = new \Sarcofag\Http\Response(200, $headers);

        return $response->withProtocolVersion($container->get('settings')['httpVersion']);
    },

    'callableResolver' => DI\get(\Slim\CallableResolver::class)
];
