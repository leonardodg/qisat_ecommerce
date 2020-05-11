<?php
use Cake\Routing\Router;

Router::plugin(
    'Repasse',
    ['path' => '/repasse'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmRepasse', 'action' => 'index']);
        $routes->connect('/wsc-repasse/:action', ['controller' => 'WscRepasse', '/:action']);
        $routes->connect('/wsc-ligamos-para-voce', ['controller' => 'WscLigamosParaVoce', 'action' => 'salvar']);

        $routes->connect('/add', ['controller' => 'EcmRepasse', 'action' => 'add']);

        $routes->connect('/categoria', ['controller' => 'EcmRepasseCategoria', 'action' => 'index']);
        $routes->connect('/categoria/:action', ['controller' => 'EcmRepasseCategoria', '/:action']);
        $routes->connect('/categoria/:action/:id', ['controller' => 'EcmRepasseCategoria', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/origem', ['controller' => 'EcmRepasseOrigem', 'action' => 'index']);
        $routes->connect('/origem/:action', ['controller' => 'EcmRepasseOrigem', '/:action']);
        $routes->connect('/origem/:action/:id', ['controller' => 'EcmRepasseOrigem', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
