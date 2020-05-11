<?php
use Cake\Routing\Router;

Router::plugin(
    'CursoPresencial',
    ['path' => '/curso-presencial'],
    function ($routes) {
        $routes->connect('/local/:action', ['controller' => 'EcmCursoPresencialLocal', '/:action']);
        $routes->connect('/local', ['controller' => 'EcmCursoPresencialLocal', 'action' => 'index']);
        $routes->connect('/local/:action/:id', ['controller' => 'EcmCursoPresencialLocal', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/turma/:action', ['controller' => 'EcmCursoPresencialTurma', '/:action']);
        $routes->connect('/turma', ['controller' => 'EcmCursoPresencialTurma', 'action' => 'index']);
        $routes->connect('/turma/:action/:id', ['controller' => 'EcmCursoPresencialTurma', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/lista-interesse/:action', ['controller' => 'EcmCursoPresencialInteresse', '/:action']);
        $routes->connect('/lista-interesse', ['controller' => 'EcmCursoPresencialInteresse', 'action' => 'index']);
        $routes->connect('/lista-interesse/:action/:id', ['controller' => 'EcmCursoPresencialInteresse', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/wsc-curso-presencial/:action', ['controller' => 'WscCursoPresencial', '/:action']);
        $routes->connect('/wsc-curso-presencial-interesse/:action', ['controller' => 'WscCursoPresencialInteresse', '/:action']);

        $routes->fallbacks('DashedRoute');
    }
);
