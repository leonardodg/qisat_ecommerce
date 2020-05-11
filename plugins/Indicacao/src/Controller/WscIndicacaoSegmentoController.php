<?php

namespace Indicacao\Controller;

use App\Controller\WscController;
use Cake\Event\Event;

class WscIndicacaoSegmentoController extends WscController
{

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    /*
    * Função responsável por listar todos os segmentos de indicações de cursos
    * Deve ser feito requisições do tipo GET, sem parâmetros:
    * http://{host}/indicacao/wsc-indicacao-segmento/listar
    *
    * Retornos:
    * 1- {'sucesso':true, 'ecmAlternativeHost': Entidades com promoções e imagens}
    *
    * */
    public function listar(){
        $this->loadModel('Indicacao.EcmIndicacaoSegmento');

        $ecmIndicacaoSegmento = $this->EcmIndicacaoSegmento->find('list', ['keyField' => 'id',
            'valueField' => 'segmento']);

        $retorno = ['sucesso'=> true, 'ecmIndicacaoSegmento'=> $ecmIndicacaoSegmento];
        $this->set(compact('retorno'));
    }
}