<?php
use Cake\Routing\Router;

Router::plugin(
    'WebService',
    ['path' => '/web-service'],
    function ($routes) {
        $routes->fallbacks('DashedRoute');
    }
);
