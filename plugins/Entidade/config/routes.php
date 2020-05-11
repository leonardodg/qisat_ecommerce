<?php
use Cake\Routing\Router;

Router::plugin(
    'Entidade',
    ['path' => '/entidade'],
    function ($routes) {
        $routes->fallbacks('DashedRoute');
    }
);
