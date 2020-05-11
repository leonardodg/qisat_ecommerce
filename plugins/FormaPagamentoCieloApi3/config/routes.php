<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamentoCieloApi3',
    ['path' => '/forma-pagamento-cielo-api3'],
    function ($routes) {
        $routes->connect('/requisicao', ['controller' => 'FormaPagamentoCieloApi3', 'action' => 'requisicao']);
        $routes->connect('/retorno', ['controller' => 'FormaPagamentoCieloApi3', 'action' => 'retorno']);
        $routes->connect('/campainha', ['controller' => 'WscFormaPagamentoCieloApi3', 'action' => 'campainha']);
        $routes->connect('/consulta', ['controller' => 'WscFormaPagamentoCieloApi3', 'action' => 'consulta']);
        $routes->fallbacks('DashedRoute');
    }
);
