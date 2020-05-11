<?php
use Cake\Routing\Router;

Router::plugin(
    'Carrinho',
    ['path' => '/carrinho'],
    function ($routes) {
        $routes->connect('/listaprodutos', ['controller' => 'EcmCarrinho', 'action' => 'listaprodutos']);

        $routes->connect('', ['controller' => 'EcmCarrinho', 'action' => 'index']);
        $routes->connect('/ecm-carrinho/:action', ['controller' => 'EcmCarrinho', '/:action']);
        $routes->connect('/:action', ['controller' => 'EcmCarrinho', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmCarrinho', '/:action'],['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:action/:id', ['controller' => 'EcmCarrinho', 'action' => 'listaprodutos']);

        $routes->connect('/wsc-carrinho/:action', ['controller' => 'WscCarrinho', '/:action']);

        $routes->connect('/transacao/relatorio-transacoes', ['controller' => 'EcmTransacao', 'action'=>'index']);
        $routes->connect('/recorrencia/relatorio-recorrencia', ['controller' => 'EcmRecorrencia', 'action'=>'index']);
        $routes->connect('/relatorio-produtos-adicionados', ['controller' => 'ProdutosAdicionados', 'action'=>'index']);
        $routes->connect('/recorrencia/view/:id', ['controller' => 'EcmRecorrencia', 'action'=>'view'],
            ['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/recorrencia/transacao-view/:id', ['controller' => 'EcmRecorrencia', 'action'=>'view'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
