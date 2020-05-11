<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('DashedRoute');

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'MdlUser', 'action' => 'login']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/home', ['controller' => 'Pages', 'action' => 'display', 'home']);
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Rotas Grupo de permissão
     */
    $routes->connect('/grupo-permissao/:action/:id', ['controller' => 'EcmGrupoPermissao', '/:action'],['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/grupo-permissao/:action', ['controller' => 'EcmGrupoPermissao', '/:action','/:id']);
    $routes->connect('/grupo-permissao', ['controller' => 'EcmGrupoPermissao', 'action' => 'index']);

    /**
     * Rotas Permissão
     */
    $routes->connect('/permissao/:action/:id', ['controller' => 'EcmPermissao', '/:action'],['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/permissao/:action', ['controller' => 'EcmPermissao', '/:action']);
    $routes->connect('/permissao', ['controller' => 'EcmPermissao', 'action' => 'index']);

    /**
     * Rotas alternativas para utilização do site
     */
    $routes->connect('/wsc-user/aceite-contrato', ['controller' => 'WscUser', 'action' => 'aceiteContrato']);
    $routes->connect('/wsc-user/remember-me', ['controller' => 'WscUser', 'action' => 'lembreteSenha']);
    $routes->connect('/wsc-user/checkAuth', ['controller' => 'WscUser', 'action' => 'checkAuth']);
    $routes->connect('/wsc-user/checkPassword', ['controller' => 'WscUser', 'action' => 'checkPassword']);
    $routes->connect('/wsc-user/updatePassword', ['controller' => 'WscUser', 'action' => 'updatePassword']);
    $routes->connect('/wsc-user/checkEmail', ['controller' => 'WscUser', 'action' => 'checkEmail']);
    $routes->connect('/wsc-user/checkCPF', ['controller' => 'WscUser', 'action' => 'checkCPF']);
    $routes->connect('/wsc-user/matriculas', ['controller' => 'WscUser', 'action' => 'matriculas']);
    $routes->connect('/wsc-user/financeiro', ['plugin' => 'Vendas', 'controller' => 'WscMinhasCompras', 'action' => 'listar']);
    $routes->connect('/wsc-user/financeiro/:action', ['plugin' => 'Vendas', 'controller' => 'WscMinhasCompras', '/:action']);
    $routes->connect('/wsc-user/carrinho', ['plugin' => 'Carrinho', 'controller' => 'WscCarrinho', 'action' => 'listar']);
    $routes->connect('/wsc-user/carrinho/:action', ['plugin' => 'Carrinho', 'controller' => 'WscCarrinho', '/:action']);
    $routes->connect('/wsc-user/certificados', ['plugin' => 'WebService', 'controller' => 'WscCertificate', 'action' => 'listar']);
    $routes->connect('/wsc-user/update', ['controller' => 'WscUser', 'action' => 'createUser']);
    $routes->connect('/wsc-user/:action', ['controller' => 'WscUser', '/:action']);
    $routes->connect('/wsc-user/:action/:id', ['controller' => 'WscUser', '/:action'],['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/newsletter/:email', ['plugin' => 'Newsletter', 'controller' => 'WscNewsletter', 'action' => 'inserir'],['pass' => ['email']]);
    $routes->connect('/identidade-visual/:email', ['plugin' => 'WebService', 'controller' => 'WscSolicitarIdentidadeVisual', 'action' => 'solicitar'],['pass' => ['email']]);
    $routes->connect('/contrato-convenio/:id', ['plugin' => 'Convenio', 'controller' => 'EcmConvenio', 'action' =>'contrato'],['pass' => ['id']]);
    $routes->connect('/repasse/contato', ['plugin' => 'Repasse', 'controller' => 'WscRepasse', 'action' =>'salvar']);
    /**
     * Rotas Usuário
     */
    $routes->connect('/users/:action', ['controller' => 'MdlUser', '/:action']);
    $routes->connect('/user/:action', ['controller' => 'MdlUser', '/:action']);
    $routes->connect('/usuario/:action', ['controller' => 'MdlUser', '/:action']);
    $routes->connect('/mdl-user/:action/:id', ['controller' => 'MdlUser', '/:action'],['id' => '\d+', 'pass' => ['id']]);
    /**
     * Rotas Usuário
     */
    $routes->connect('/pagamento', ['plugin' => 'FormaPagamento',
        'controller' => 'WscFormaPagamento', 'action' => 'retorno']);
    $routes->connect('/wsc-forma-pagamento/retorno', ['plugin' => 'FormaPagamento',
        'controller' => 'WscFormaPagamento', 'action' => 'retorno']);

    /**
     * Boleto
     */
    $routes->connect('/boleto', ['plugin' => 'FormaPagamentoBoletoPhp', 'controller' => 'FormaPagamentoBoletoPhp', 'action' => 'boleto']);
    $routes->connect('/boleto-registrado', ['plugin' => 'FormaPagamentoBoletoRegistrado', 'controller' => 'FormaPagamentoBoletoRegistrado', 'action' => 'boleto']);

    /**
     * Registro de ações do ecommerce
     */
    $routes->connect('/log-acoes', ['controller' => 'EcmLogAcao', 'action' => 'index']);
    $routes->connect('/log-acoes/index', ['controller' => 'EcmLogAcao', 'action' => 'index']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
Router::extensions('json');
