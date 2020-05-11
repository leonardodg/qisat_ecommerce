<?php
use Cake\Routing\Router;

Router::plugin(
    'Vendas',
    ['path' => '/vendas'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmVenda', 'action' => 'index']);

        $routes->connect('/vencimentos', ['controller' => 'EcmVenda', 'action' => 'vencimentos']);
        
        $routes->connect('/:action/:id', ['controller' => 'EcmVenda', '/:action'],['id' => '\d+', 'pass' => ['id']]);
        
        $routes->connect('/presencial', ['controller' => 'EcmVendaPresencial', 'action' => 'index']);
        $routes->connect('/presencial/:action/:id', ['controller' => 'EcmVendaPresencial', '/:action'],['id' => '\d+', 'pass' => ['id']]);
        
        $routes->connect('/boleto', ['controller' => 'EcmVendaBoleto', 'action' => 'index']);
        
        $routes->connect('/wsc-venda/import', ['controller' => 'WscVenda', 'action' => 'import']);
        $routes->connect('/wsc-venda/:action/:id', ['controller' => 'WscVenda', '/:action'],['id' => '\d+', 'pass' => ['id']]);
        
        $routes->connect('/wsc-minhas-compras/:action/:id', ['controller' => 'WscMinhasCompras', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
