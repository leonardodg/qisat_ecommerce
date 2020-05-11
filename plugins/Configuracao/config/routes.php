<?php
use Cake\Routing\Router;

Router::plugin(
    'Configuracao',
    ['path' => '/configuracao'],
    function ($routes) {

        $routes->connect('/rede-social', ['controller' => 'EcmRedeSocial', 'action' => 'index']);
        $routes->connect('/rede-social/:action', ['controller' => 'EcmRedeSocial', '/:action']);
        $routes->connect('/rede-social/:action/:id', ['controller' => 'EcmRedeSocial', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
