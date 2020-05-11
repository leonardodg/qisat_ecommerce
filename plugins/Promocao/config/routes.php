<?php
use Cake\Routing\Router;

Router::plugin(
    'Promocao',
    ['path' => '/promocao'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmPromocao', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmPromocao', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmPromocao', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
