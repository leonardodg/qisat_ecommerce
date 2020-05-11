<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamentoFastConnect',
    ['path' => '/forma-pagamento-fast-connect'],
    function ($routes) {
        $routes->connect('/requisicao', ['controller' => 'FormaPagamentoFastConnect', 'action' => 'requisicao']);
        $routes->connect('/cancelar', ['controller' => 'FormaPagamentoFastConnect', 'action' => 'cancelar']);
        $routes->fallbacks('DashedRoute');
    }
);
