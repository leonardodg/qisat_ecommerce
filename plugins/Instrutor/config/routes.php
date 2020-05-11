<?php
use Cake\Routing\Router;

Router::plugin(
    'Instrutor',
    ['path' => '/instrutor'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmInstrutor', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmInstrutor', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmInstrutor', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/artigo/:action', ['controller' => 'EcmInstrutorArtigo', '/:action']);
        $routes->connect('/artigo/index/:id', ['controller' => 'EcmInstrutorArtigo', 'action' => 'index'],['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/artigo/:action/:id', ['controller' => 'EcmInstrutorArtigo', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/rede-social/:action', ['controller' => 'EcmInstrutorRedeSocial', '/:action']);
        $routes->connect('/rede-social/index/:id', ['controller' => 'EcmInstrutorRedeSocial', 'action' => 'index'],['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/rede-social/:action/:id', ['controller' => 'EcmInstrutorRedeSocial', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/wsc-instrutor/:action', ['controller' => 'WscInstrutor', '/:action']);
        $routes->connect('/wsc-instrutor/:action/:id', ['controller' => 'WscInstrutor', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
