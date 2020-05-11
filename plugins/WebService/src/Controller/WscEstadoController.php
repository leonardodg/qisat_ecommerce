<?php

namespace WebService\Controller;

use App\Controller\WscController;
use Cake\Event\Event;

class WscEstadoController extends WscController
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
    * Função reponsável por listar os estados
    * Deve ser feito requisições do tipo GET:
    * http://{host}/web-service/wsc-estado/listar
    *
    * Retornos:
    * 1- {'sucesso':true, estado:lista de estados}
    *
    * */
    public function listar(){
        $this->loadModel('WebService.MdlEstado');

        $listaEstado = $this->MdlEstado->find('all')->orderAsc('nome')->toList();
        $retorno = ['sucesso' => true, 'estado' => $listaEstado];

        $this->set(compact('retorno'));
    }
}