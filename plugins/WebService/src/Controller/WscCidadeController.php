<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 13/09/2016
 * Time: 10:05
 */

namespace WebService\Controller;

use App\Controller\WscController;
use Cake\Event\Event;

class WscCidadeController extends WscController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);

    }

    /*
    * Função reponsável por listar as cidade de um estado
    * Deve ser feito requisições do tipo GET, informando os seguintes parâmetros:
    * http://{host}/web-service/wsc-cidade/listar/{id ou uf do estado}
    *
    * Retornos:
    * 1- {'sucesso':true, cidade:lista de cidades}
    * 2- {'sucesso':false, 'mensagem': 'Parâmetro estado não informado'}
    * 3- {'sucesso':false, 'mensagem': 'Parâmetro estado inválido'}
    *
    * */
    public function listar($estado = null){
        $retorno = ['sucesso' => false, 'mensagem' => __('Parâmetro estado não informado')];

        if(!is_null($estado)) {
            $this->loadModel('WebService.MdlCidade');

            $where = [];
            if(is_numeric($estado))
                $where['MdlCidade.uf'] = $estado;
            elseif(is_string($estado) && strlen($estado) == 2)
                $where['MdlEstado.uf'] = $estado;

            if(!empty($where)) {
                $listaCidade = $this->MdlCidade->find('all')->contain(['MdlEstado'])
                    ->where($where)->orderAsc('MdlCidade.nome')
                    ->toList();
                $retorno = ['sucesso' => true, 'cidade' => $listaCidade];
            }else{
                $retorno = ['sucesso' => false, 'mensagem' => __('Parâmetro estado inválido')];
            }
        }

        $this->set(compact('retorno'));
    }
}