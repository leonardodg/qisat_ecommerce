<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamentoSuperPayV3',
    ['path' => '/forma-pagamento-super-pay-v3'],
    function ($routes) {
        $routes->connect('/requisicao', ['controller' => 'FormaPagamentoSuperPayV3', 'action' => 'requisicao']);
        $routes->connect('/retorno', ['controller' => 'FormaPagamentoSuperPayV3', 'action' => 'retorno']);
        $routes->connect('/cancelar', ['controller' => 'FormaPagamentoSuperPayV3', 'action' => 'cancelar']);
        $routes->fallbacks('DashedRoute');
    }
);
