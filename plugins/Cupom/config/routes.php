<?php
use Cake\Routing\Router;

Router::plugin(
    'Cupom',
    ['path' => '/cupom'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmCupom', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmCupom', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmCupom', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/validar', ['controller' => 'WscCupom', 'action' => 'validar-cupom']);
        $routes->connect('/validar/:chave', ['controller' => 'WscCupom', 'action' => 'validar-cupom'],['pass' => ['chave']]);
        $routes->connect('/validar', ['controller' => 'WscCupom', 'action' => 'validar-cupom'],['pass' => ['chave']]);

        $routes->connect('/gerar/:chave/:email', ['controller' => 'WscCupom', 'action' => 'gerar-cupom'],['pass' => ['chave', 'email']]);

        $routes->fallbacks('DashedRoute');
    }
);
