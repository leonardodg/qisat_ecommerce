<?php

namespace Indicacao\Controller;

use App\Controller\WscController;
use Cake\Event\Event;

class WscIndicacaoCursoController extends WscController
{

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    /*
    * Função responsável por adicionar uma indicação a um curso
    * Deve ser feito requisições do tipo POST, com parâmetros:
    * http://{host}/indicacao/wsc-indicacao-curso/add
    *
    * Retornos:
    * 1- {'sucesso': true, 'ecmIndicacaoCurso': Indicação de curso registrada com sucesso}
    * 2- {'sucesso': false, 'mensagem': Requisição POST necessaria}
    * 3- {'sucesso': false, 'mensagem': A indicação de curso não pode ser registrada}
    * 4- {'sucesso': false, 'mensagem': Informe os parâmetros da indicação do curso}
    * 5- {'sucesso': false, 'mensagem': Informe o parâmetro Segmento}
    * 6- {'sucesso': false, 'mensagem': Informe o parâmetro Tema}
    *
    * */
    public function add(){
        $retorno = ['sucesso' => false, 'mensagem' => 'Requisição POST necessaria'];

        if($this->request->is('post')){
            if(empty($this->request->data)) {
                $retorno['mensagem'] = 'Informe os parâmetros da indicação do curso';
            } else if (!isset($this->request->data['segmento'])) {
                $retorno['mensagem'] = 'Informe o parâmetro Segmento';
            } else if (!isset($this->request->data['tema'])) {
                $retorno['mensagem'] = 'Informe o parâmetro Tema';
            } else {
                $retorno['mensagem'] = 'A indicação de curso não pode ser registrada';

                $this->loadModel('Indicacao.EcmIndicacaoCurso');
                if (is_numeric($this->request->data['segmento'])) {
                    $this->request->data['ecm_indicacao_segmento_id'] = $this->request->data['segmento'];
                } else {
                    $this->request->data['ecm_indicacao_segmento_id'] = $this->EcmIndicacaoCurso
                        ->EcmIndicacaoSegmento->find('all', ['fields' => 'id',
                            'conditions' => ['segmento' => $this->request->data['segmento']]])->first()['id'];
                }

                $ecmIndicacaoCurso = $this->EcmIndicacaoCurso->newEntity();

                $this->request->data['ecm_alternative_host_id'] = $this->validaAlternativeHost();
                if (is_numeric($this->request->data['ecm_alternative_host_id'])) {
                    $this->request->data['timemodified'] = new \DateTime();

                    $usuario = $this->Auth->user();
                    if(isset($usuario))
                        $this->request->data['mdl_user_id'] = $usuario['id'];

                    $ecmIndicacaoCurso = $this->EcmIndicacaoCurso->patchEntity($ecmIndicacaoCurso,
                        $this->request->data, ['associated' => ['EcmIndicacaoSegmento']]);

                    if ($this->EcmIndicacaoCurso->save($ecmIndicacaoCurso))
                        $retorno = ['sucesso' => true, 'mensagem' => 'Indicação de curso registrada com sucesso'];

                }
            }
        }

        $this->set(compact('retorno'));
    }
}