 <?php
use Cake\Routing\Router;

Router::plugin(
    'Convenio',
    ['path' => '/convenio'],
    function ($routes) {

        $routes->connect('', ['controller' => 'EcmConvenio', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmConvenio', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmConvenio', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/lista-interesse/:id', ['controller' => 'EcmConvenio', 'action'=>'lista-interesse'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/convenio-contrato/contrato/:id', ['controller' => 'EcmConvenioContrato', 'action' => 'contrato'],['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
