<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\Router;

Router::plugin(
    'ContaAzul',
    ['path' => '/conta-azul'],
    function ($routes) {
        $routes->connect('/', ['controller' => 'ContaAzul', 'action' => 'index']);
        $routes->connect('/retorno', ['controller' => 'ContaAzul', 'action' => 'callback']);
        $routes->connect('/list-services', ['controller' => 'ContaAzul', 'action' => 'listServices']);
        $routes->connect('/list-clients', ['controller' => 'ContaAzul', 'action' => 'listClients']);
        $routes->connect('/list-products', ['controller' => 'ContaAzul', 'action' => 'listProducts']);
        $routes->connect('/export-clients', ['controller' => 'ContaAzul', 'action' => 'exportClients']);
        $routes->connect('/export-clients/:ids', ['controller' => 'ContaAzul', 'action' => 'exportClients'], ['pass' => ['ids']]);
        $routes->connect('/export-products', ['controller' => 'ContaAzul', 'action' => 'exportProducts']);
        $routes->connect('/export-products/:ids', ['controller' => 'ContaAzul', 'action' => 'exportProducts'], ['pass' => ['ids']]);
        $routes->connect('/export-sales', ['controller' => 'ContaAzul', 'action' => 'exportSales']);
        $routes->connect('/export-services', ['controller' => 'ContaAzul', 'action' => 'exportServices']);
        $routes->connect('/service', ['controller' => 'ContaAzul', 'action' => 'setService']);
        $routes->connect('/service/:id', ['controller' => 'ContaAzul', 'action' => 'setService'], ['pass' => ['id']]);
        $routes->connect('/product', ['controller' => 'ContaAzul', 'action' => 'setProduct']);
        $routes->connect('/product/:id', ['controller' => 'ContaAzul', 'action' => 'setProduct'], ['pass' => ['id']]);
        $routes->connect('/del-service/:id', ['controller' => 'ContaAzul', 'action' => 'delService'], ['pass' => ['id']]);
        $routes->connect('/del-product/:id', ['controller' => 'ContaAzul', 'action' => 'delProduct'], ['pass' => ['id']]);
        $routes->connect('/del-client/:id', ['controller' => 'ContaAzul', 'action' => 'delClient'], ['pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
