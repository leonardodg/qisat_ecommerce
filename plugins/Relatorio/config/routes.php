<?php
use Cake\Routing\Router;

Router::plugin(
    'Relatorio',
    ['path' => '/relatorio'],
    function ($routes) {
        $routes->fallbacks('DashedRoute');
    }
);
