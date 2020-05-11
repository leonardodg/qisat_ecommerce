<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamentoBoletoRegistrado',
    ['path' => '/forma-pagamento-boleto-registrado'],
    function ($routes) {
        $routes->connect('/:action', ['controller' => 'FormaPagamentoBoletoRegistrado', '/:action']);
        $routes->fallbacks('DashedRoute');
    }
);
