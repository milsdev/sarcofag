<?php
/*  Copyright Mil's (http://milsdev.com/)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
use Interop\Container\ContainerInterface;

return [
    'settings' => function () {
            return require __DIR__ . '/../config.inc.php';
    },


    PDO::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['db'];
        $pdo = new \PDO("mysql:host={$settings['host']};dbname={$settings['dbname']};charset=utf8",
                        $settings['user'],
                        $settings['password']);

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    },

    Sarcofag\View\Renderer\SimpleRenderer::class => function (ContainerInterface $container) {
        $helper = $container->get(\Sarcofag\View\Helper\HelperManager::class);
        $renderer = new \Sarcofag\View\Renderer\SimpleRenderer($helper,
                                                               ['admin' => __DIR__ . '/../../src/admin/view',
                                                                'theme' => __DIR__ . '/../../src/theme/view'],
                                                               $container->get('Sarcofag\Service\API\WP'));
        
        $layoutHelper = new \Sarcofag\View\Helper\LayoutHelper($renderer);
        $helper->addViewHelper('layout', $layoutHelper);
        return $renderer;
    },

    'errorHandler' => function (ContainerInterface $container) {
            return new \Slim\Handlers\Error($container->get('settings')['displayErrorDetails']);
    },

    'notFoundHandler' => function () {
            return new \Slim\Handlers\NotFound();
    },

    'notAllowedHandler' => function () {
            return new \Slim\Handlers\NotAllowed();
    },

    'environment' => function () {
        return new \Slim\Http\Environment($_SERVER);
    },

    'request' => function (ContainerInterface $container) {
        return \Slim\Http\Request::createFromEnvironment($container->get('environment'));
    },

    'response' => function (ContainerInterface $container) {
        $headers = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
        $response = new \Slim\Http\Response(200, $headers);

        return $response->withProtocolVersion($container->get('settings')['httpVersion']);
    },

    'callableResolver' => function (ContainerInterface $container) {
        return new \Slim\CallableResolver($container);
    },

    'renderer' => function (ContainerInterface $container) {
        return $container->get(Slim\Views\PhpRenderer::class);
    }
];
