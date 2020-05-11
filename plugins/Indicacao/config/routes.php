<?php
use Cake\Routing\Router;

Router::plugin(
    'Indicacao',
    ['path' => '/indicacao'],
    function ($routes) {
        $routes->fallbacks('DashedRoute');
    }
);
