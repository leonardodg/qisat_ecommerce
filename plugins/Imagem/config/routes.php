<?php
use Cake\Routing\Router;

Router::plugin(
    'Imagem',
    ['path' => '/imagem'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmImagem', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmImagem', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmImagem', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
