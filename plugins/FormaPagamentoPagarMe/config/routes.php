<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamentoPagarMe',
    ['path' => '/forma-pagamento-pagar-me'],
    function ($routes) {
        $routes->connect('/requisicao', ['controller' => 'FormaPagamentoPagarMe', 'action' => 'requisicao']);
        $routes->connect('/retorno', ['controller' => 'FormaPagamentoPagarMe', 'action' => 'retorno']);
        $routes->connect('/campainha', ['controller' => 'WscFormaPagamentoPagarMe', 'action' => 'campainha']);
        $routes->connect('/consulta', ['controller' => 'WscFormaPagamentoPagarMe', 'action' => 'consulta']);
        $routes->fallbacks('DashedRoute');
    }
);
