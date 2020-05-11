<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamentoCieloApi2',
    ['path' => '/forma-pagamento-cielo-api2'],
    function ($routes) {
        $routes->connect('/requisicao', ['controller' => 'FormaPagamentoCieloApi2', 'action' => 'requisicao']);
        $routes->connect('/forma-pagamento-cielo-api2/retorno/:id',
            ['controller' => 'FormaPagamentoCieloApi2', 'action' => 'retorno'],
            ['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/campainha', ['controller' => 'WscFormaPagamentoCieloApi2', 'action' => 'campainha']);
        $routes->fallbacks('DashedRoute');
    }
);
