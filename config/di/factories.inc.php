<?php
return [
    PDO::class => function (\Interop\Container\ContainerInterface $container) {
        $settings = $container->get('settings')['db'];
        $pdo = new \PDO("mysql:host={$settings['host']};dbname={$settings['dbname']};charset=utf8",
                        $settings['user'],
                        $settings['password']);

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    },

    'Renderer' => function (\Interop\Container\ContainerInterface $container) {
        /* @var $themeEntity WP_Theme */
        $themeEntity = $container->get(\Sarcofag\API\WP::class)->wp_get_theme();
        $themeDirectory = $themeEntity->get_template_directory() . '/src/api/view';

        $paths = array_map('realpath',
                            array_merge($container->get('template.paths'),
                                        [$themeEntity->get_template() => $themeDirectory]));
        
        $renderer = new \Sarcofag\View\Renderer\SimpleRenderer($container->get('HelperManager'),
                                                               $paths,
                                                               $container,
                                                               $container->get('Sarcofag\API\WP'));
        return $renderer;
    }
];
