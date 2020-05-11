<?php

namespace DuvidasFrequentes\Controller;

use App\Controller\WscController;
use Cake\Event\Event;

class WscDuvidasFrequentesController extends WscController
{

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    /*
    * Função responsável por listar todos os descontos de todas as entidades conveniadas
    * Deve ser feito requisições do tipo GET, sem parâmetros:
    * http://{host}/entidade/wsc-entidade/entidades-conveniadas
    *
    * Retornos:
    * 1- {'ecmDuvidasFrequentes'}
    *
    * */
    public function listar(){
        $this->loadModel('DuvidasFrequentes.EcmDuvidasFrequentes');

        $retorno = $this->EcmDuvidasFrequentes->find('list', ['keyField' => 'ordem',
            'valueField' => function($q){
                return ['id' => $q->id, 'titulo' => $q->titulo, 'url' => $q->url];
            },
        ]);

        $this->set(compact('retorno'));
    }
}