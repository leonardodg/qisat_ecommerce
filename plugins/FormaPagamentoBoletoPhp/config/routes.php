<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamentoBoletoPhp',
    ['path' => '/forma-pagamento-boleto-php'],
    function ($routes) {
        $routes->connect('/requisicao', ['controller' => 'FormaPagamentoBoletoPhp', 'action' => 'requisicao']);
        $routes->connect('/boleto', ['controller' => 'FormaPagamentoBoletoPhp', 'action' => 'boleto']);
        $routes->connect('/boleto/:id', ['controller' => 'FormaPagamentoBoletoPhp', 'action' => 'boleto'],
            ['id' => '\d+', 'pass' => ['id']]);
        $routes->fallbacks('DashedRoute');
    }
);
