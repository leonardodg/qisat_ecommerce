<?php
use Cake\Routing\Router;

Router::plugin(
    'Newsletter',
    ['path' => '/newsletter'],
    function ($routes) {

        $routes->connect('', ['controller' => 'EcmNewsletter', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmNewsletter', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmNewsletter', '/:action'],['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:controller/:action/:id', ['/:controller', '/:action'],['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:controller/:action', ['/:controller', '/:action']);

        $routes->fallbacks('DashedRoute');
    }
);
