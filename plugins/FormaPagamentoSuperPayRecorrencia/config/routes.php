<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamentoSuperPayRecorrencia',
    ['path' => '/forma-pagamento-super-pay-recorrencia'],
    function ($routes) {
        $routes->connect('/requisicao', ['controller' => 'FormaPagamentoSuperPayRecorrencia', 'action' => 'requisicao']);
        $routes->connect('/cancelar', ['controller' => 'FormaPagamentoSuperPayRecorrencia', 'action' => 'cancelar']);
        $routes->connect('/campainha', ['controller' => 'WscFormaPagamentoSuperPayRecorrencia', 'action' => 'campainha']);
        $routes->fallbacks('DashedRoute');
    }
);
