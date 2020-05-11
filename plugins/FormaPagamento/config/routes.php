<?php
use Cake\Routing\Router;

Router::plugin(
    'FormaPagamento',
    ['path' => '/forma-pagamento'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmFormaPagamento', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmFormaPagamento', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmFormaPagamento', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/tipo-pagamento/:action', ['controller' => 'EcmTipoPagamento', '/:action']);
        $routes->connect('/tipo-pagamento', ['controller' => 'EcmTipoPagamento', 'action' => 'index']);
        $routes->connect('/tipo-pagamento/:action/:id', ['controller' => 'EcmTipoPagamento', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/operadora-pagamento/:action', ['controller' => 'EcmOperadoraPagamento', '/:action']);
        $routes->connect('/operadora-pagamento', ['controller' => 'EcmOperadoraPagamento', 'action' => 'index']);
        $routes->connect('/operadora-pagamento/:action/:id', ['controller' => 'EcmOperadoraPagamento', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/plugin-pagamento/:action', ['controller' => 'EcmPluginPagamento', '/:action']);
        $routes->connect('/plugin-pagamento', ['controller' => 'EcmPluginPagamento', 'action' => 'index']);
        $routes->connect('/plugin-pagamento/:action/:id', ['controller' => 'EcmPluginPagamento', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/wsc-forma-pagamento/:action', ['controller' => 'WscFormaPagamento', '/:action']);

        $routes->fallbacks('DashedRoute');
    }
);
