<?php
use Cake\Routing\Router;

Router::plugin(
    'Publicidade',
    ['path' => '/publicidade'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmPublicidade', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmPublicidade', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmPublicidade', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:action/:id/:idturma', ['controller' => 'EcmPublicidade', '/:action'],
            ['id' => '\d+', 'idturma' => '\d+', 'pass' => ['id', 'idturma']]);

        //$routes->connect('/wsc-publicidade/:action', ['controller' => 'WscPublicidade', '/:action']);
        $routes->connect('/wsc-publicidade/:action/:id', ['controller' => 'WscPublicidade', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/wsc-publicidade/:action/:id/:idturma', ['controller' => 'WscPublicidade', '/:action'],
            ['id' => '\d+', 'idturma' => '\d+', 'pass' => ['id', 'idturma']]);

        $routes->fallbacks('DashedRoute');
    }
);
