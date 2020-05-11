<?php
namespace App\View\Cell;

use Cake\Routing\Router;
use Cake\View\Cell;

/**
 * Menu cell
 */
class MenuCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];
    protected $exibir = ['AltoqiLab' => ['edit'], 'Trilha' => ['edit']];
    protected $ocultar = ['edit', 'delete', 'view', 'lista-usuario-json',
            'deleteArquivos', 'deleteConteudo', 'requisicao', 'retorno', 'boleto',
            'EcmCarrinho' => ['add', 'remove', 'index', 'listaprodutos','produtosAltoqi', 'listaCupom', 'montarcarrinho',
                'validarCupom', 'confirmardados', 'contrato', 'prazoExtra', 'agendamento', 'view', 'edit'],
            'EcmConvenio' => ['gerarContrato', 'contrato', 'listaInteresse'],
            'EcmConvenioContrato' => ['contrato'],
            'EcmInstrutorArtigo' => [],
            'EcmInstrutorRedeSocial' => [],
            'EcmLogAcao' => [],
            'EcmProduto' => ['alterarStatus'],
            'AltoqiLab' => ['add', 'addCourseOrdem'],
            'EcmPublicidade' => ['arquivos'],
            'EcmRepasse' => ['alterarStatus', 'alterarResponsavel', 'atribuirResponsavel'],
            'EcmVendaPresencial' => ['listaVendas', 'listaEmail', 'add'],
            'MdlFase' => [],
            'FormaPagamentoSuperPayV3' => [],
            'FormaPagamentoSuperPayRecorrencia' => [],
            'MdlUser' => ['lista-usuario-json', 'importarUsuario', 'editUsuarioAltoqi'],
            'ContaAzul' => ['callback', 'exportClients', 'exportProducts', 'exportSales', 'exportServices', 'setService', 'setProduct', 'delService', 'delProduct', 'delClient']
    ];
    //protected $organizar = ['Pages' => ['display'], 'MdlUser'];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
        $this->loadModel('EcmConfig');
        
        $ocultar = $this->ocultar;
        $exibir  = $this->exibir;

        $pluginReq = isset($this->request->params['plugin']) ? $this->request->params['plugin'] : "";
        $controllerReq = $this->request->params['controller'];
        //$actionReq = $this->request->params['action'];

        if(!empty($this->request->params['pass']))
            $id = array_shift($this->request->params['pass']);

        $permissoes = $this->request->session()->read('Auth.User.permissoes');
        if(!isset($permissoes) || isset($permissoes['acesso_total'])){
            $this->loadModel('Permissao.EcmPermissao');
            $ecmPermissoes = $this->EcmPermissao->find('all');
            if(!isset($permissoes)){
                $ecmPermissoes->where(['restricao="site"']);
            }else{
                unset($permissoes['acesso_total']);
            }
            foreach($ecmPermissoes as $permissao) {
                $plugin = isset($permissao->plugin) ? $permissao->plugin : "";
                $permissoes[$plugin][$permissao->controller][$permissao->action] = $permissao->label;
            }
        }

        foreach($permissoes as $plugin => $controllers) {
            foreach ($controllers as $controller => $actions) {
                foreach ($actions as $action => $label) {
                    if(in_array($action, $ocultar) && ($controller != $controllerReq || !isset($id))){
                        if(!array_key_exists($controller, $exibir) || !in_array($action, $exibir[$controller]))
                            unset($permissoes[$plugin][$controller][$action]);
                    }
                }
                if (strpos($controller, 'Wsc') !== false) {
                    unset($permissoes[$plugin][$controller]);
                } else if (array_key_exists($controller, $ocultar)) {
                    if (empty($ocultar[$controller])) {
                        unset($permissoes[$plugin][$controller]);
                    } else {
                        foreach ($ocultar[$controller] as $action) {
                            unset($permissoes[$plugin][$controller][$action]);
                        }
                    }
                }
            }
        }

        $menu = [];

        if(array_key_exists($pluginReq, $permissoes)) {
            if(array_key_exists($controllerReq, $permissoes[$pluginReq])) {

                foreach ($permissoes[$pluginReq][$controllerReq] as $action => $label) {
                    $link = ['_base' => false, 'plugin' => $pluginReq == "" ? false : $pluginReq,
                        'controller' => $controllerReq, 'action' => $action];
                    if (in_array($action, $ocultar) && isset($id)) {
                        $link[] = $id;
                    }
                    try {
                        $menu[$pluginReq == "" ? "Home" : $pluginReq][$label] = Router::url($link);
                    } catch (\Exception $e) {}
                }
                unset($permissoes[$pluginReq][$controllerReq]);
                if(empty($permissoes[$pluginReq]))
                    unset($permissoes[$pluginReq]);
            }
        }
        foreach($permissoes as $plugin => $controllers) {
            foreach ($controllers as $controller => $actions) {
                foreach ($actions as $action => $label) {
                    try {
                        $menu[$plugin == "" ? "Home" : $plugin][$label] = Router::url(['_base' => false,
                            'plugin' => $plugin == "" ? false : $plugin, 'controller' => $controller, 'action' => $action]);
                    } catch (\Exception $e) {}
                }
            }
        }

        $linkMoodle = $this->EcmConfig->find('all')->where(['nome' => 'dominio_acesso_moodle'])->first()->valor;
        $this->set('linkMoodle', $linkMoodle);

        ksort($menu);
        $this->set(compact('menu'));
        $this->set('_serialize', ['menu']);
    }

    /**
     * Tratar Nome method.
     *
     * @param string|null $controller String controller.
     * @param string|null $action String action.
     * @return String
     */
    private function tratarNome($controller, $action){
        $nome = preg_replace('(Ecm|Mdl)', '', $controller . ' ' . $action);
        $nome = preg_replace('/(?<!\ )[A-Z]/', ' $0', $nome);
        return $nome;
    }
}
