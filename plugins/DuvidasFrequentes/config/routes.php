<?php
use Cake\Routing\Router;

Router::plugin(
    'DuvidasFrequentes',
    ['path' => '/duvidas-frequentes'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmDuvidasFrequentes', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmDuvidasFrequentes', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmDuvidasFrequentes', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
