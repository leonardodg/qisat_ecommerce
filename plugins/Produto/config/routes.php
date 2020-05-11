<?php
use Cake\Routing\Router;

Router::plugin(
    'Produto',
    ['path' => '/produto'],
    function ($routes) {
        $routes->connect('', ['controller' => 'EcmProduto', 'action' => 'index']);
        $routes->connect('/:action', ['controller' => 'EcmProduto', '/:action']);
        $routes->connect('/:action/:id', ['controller' => 'EcmProduto', '/:action'],['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/:action/:id/:parametro/:status', ['controller' => 'EcmProduto', '/:action'],['id' => '\d+',
            'parametro' => '(habilitado|visivel)', 'status' => '(true|false|excluido)', 'pass' => ['id', 'parametro', 'status']]);

        $routes->connect('/tipo-produto/:action', ['controller' => 'EcmTipoProduto', '/:action']);
        $routes->connect('/tipo-produto', ['controller' => 'EcmTipoProduto', 'action' => 'index']);
        $routes->connect('/tipo-produto/:action/:id', ['controller' => 'EcmTipoProduto', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/ordenar-produto', ['controller' => 'EcmProdutoEcmTipoProdutoEcmAlternativeHost', 'action' => 'index']);

        $routes->connect('/destaques', ['controller' => 'EcmProdutoEcmTipoProdutoEcmAlternativeHost', 'action' => 'destaques']);

        $routes->connect('/produto-info/:action', ['controller' => 'EcmProdutoInfo', '/:action']);
        $routes->connect('/produto-info/:action/:id', ['controller' => 'EcmProdutoInfo', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/wsc-produto/*:action', ['controller' => 'WscProduto', '/:action']);
        $routes->connect('/wsc-produto/:action/:id', ['controller' => 'WscProduto', '/:action'],['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/fase/edit/:id', ['controller' => 'MdlFase', 'action' => 'edit'],['id' => '\d+', 'pass' => ['id']]);
        $routes->connect('/fase/delete/:mdl_fase_id/:mdl_course_id/:mdl_course_conclusion_id',
            ['controller' => 'EcmProduto', 'action' => 'delete'],
            ['mdl_fase_id' => '\d+', 'mdl_course_id' => '\d+', 'mdl_course_conclusion_id' => '\d+',
                'pass' => ['mdl_fase_id', 'mdl_course_id', 'mdl_course_conclusion_id']]);

        $routes->connect('/curso-online', ['controller' => 'CursoOnline', 'action' => 'index']);
        $routes->connect('/curso-online/add', ['controller' => 'CursoOnline', 'action' => 'add']);
        $routes->connect('/curso-online/:action/:id', ['controller' => 'CursoOnline', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/curso-presencial', ['controller' => 'CursoPresencial', 'action' => 'index']);
        $routes->connect('/curso-presencial/add', ['controller' => 'CursoPresencial', 'action' => 'add']);
        $routes->connect('/curso-presencial/:action/:id', ['controller' => 'CursoPresencial', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/pacote', ['controller' => 'Pacote', 'action' => 'index']);
        $routes->connect('/pacote/add', ['controller' => 'Pacote', 'action' => 'add']);
        $routes->connect('/pacote/:action/:id', ['controller' => 'Pacote', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/prazo-extra', ['controller' => 'PrazoExtra', 'action' => 'index']);
        $routes->connect('/prazo-extra/add', ['controller' => 'PrazoExtra', 'action' => 'add']);
        $routes->connect('/prazo-extra/:action/:id', ['controller' => 'PrazoExtra', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/serie-online', ['controller' => 'SerieOnline', 'action' => 'index']);
        $routes->connect('/serie-online/add', ['controller' => 'SerieOnline', 'action' => 'add']);
        $routes->connect('/serie-online/:action/:id', ['controller' => 'SerieOnline', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/item-serie', ['controller' => 'ItemSerie', 'action' => 'index']);
        $routes->connect('/item-serie/add', ['controller' => 'ItemSerie', 'action' => 'add']);
        $routes->connect('/item-serie/:action/:id', ['controller' => 'ItemSerie', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/produto-altoqi', ['controller' => 'ProdutoAltoqi', 'action' => 'index']);
        $routes->connect('/produto-altoqi/add', ['controller' => 'ProdutoAltoqi', 'action' => 'add']);
        $routes->connect('/produto-altoqi/:action/:id', ['controller' => 'ProdutoAltoqi', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/altoqi-lab', ['controller' => 'AltoqiLab', 'action' => 'index']);
        $routes->connect('/altoqi-lab/:action/:id', ['controller' => 'AltoqiLab', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->connect('/trilha', ['controller' => 'Trilha', 'action' => 'index']);
        $routes->connect('/trilha/:action/:id', ['controller' => 'Trilha', '/:action'],
            ['id' => '\d+', 'pass' => ['id']]);

        $routes->fallbacks('DashedRoute');
    }
);
