<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamentoSuperPay',
    ['path' => '/forma-pagamento-super-pay'],
    function ($routes) {
        $routes->connect('/requisicao', ['controller' => 'FormaPagamentoSuperPay', 'action' => 'requisicao']);
        $routes->connect('/retorno', ['controller' => 'FormaPagamentoSuperPay', 'action' => 'retorno']);
        $routes->fallbacks('DashedRoute');
    }
);
