<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 06/03/2017
 * Time: 10:33
 */

namespace WebService\Controller;


use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Network\Http\Client;

class WscInscricaoController extends WscController
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
    * Serviço para matrícular um usuário em um curso gratuito
    * Deve ser feito uma requisições do tipo POST  para https://{host}/web-service/wsc-inscricao/inscrever,
    * informando os seguintes parâmetros no formato JSON:
    *{
    * produto: id do produto referente ao curso
    *}
    *
    * Retornos:
    * 1- {'sucesso':true, 'mensagem': 'Inscrição realizada com sucesso', 'link': link de acesso ao curso}
    * 2- {'sucesso':true, 'mensagem': 'Usuário já matrículado', 'link': link de acesso ao curso}
    * 3- {'sucesso':false, 'mensagem': 'Parâmetro produto não informado'}
    * 4- {'sucesso':false, 'mensagem': 'Não foi possível realizar a inscrição'}
    * 5- {'sucesso':false, 'mensagem': 'Produto não encontrado'}
    *
    * */
    public function inscrever(){
        $this->loadModel('Produto.EcmProduto');

        $retorno = ['sucesso' => false, 'mensagem' => __('Parâmetro produto não informado')];

        $idProduto = $this->request->data('produto');

        if(!is_null($idProduto)) {
            $produto = $this->EcmProduto
                ->find()
                ->matching('EcmTipoProduto', function ($q) {
                    return $q->where([ 'OR' => [ ['EcmTipoProduto.id' => 8], ['EcmTipoProduto.id' => 53 ]]])
                        ->select(['id_tipo' => 'EcmTipoProduto.id']);
                })
                ->matching('MdlCourse', function ($q) {
                    return $q->select(['id_curso' => 'MdlCourse.id']);
                })
                ->where([
                    'EcmProduto.id' => $idProduto,
                    'EcmProduto.refcurso' => 'true'
                ])
                ->first();

            if (!is_null($produto)) {
                $this->loadModel('WebService.MdlCourse');
                $this->loadModel('Configuracao.EcmConfig');

                $dominioMoodle = $this->EcmConfig
                    ->find()
                    ->where(['nome' => 'dominio_acesso_moodle'])
                    ->first()->valor;

                $idUsuario = $this->Auth->user('id');
                $idCurso = $produto->get('id_curso');

                $matriculado = $this->verificaInscricao($idCurso, $idUsuario);

                $linkAcesso = 'https://'.$dominioMoodle.'/course/view.php?id='.$idCurso;

                if (!$matriculado){
                    $curso = $this->MdlCourse
                        ->find()
                        ->matching('MdlEnrol', function ($q) {
                            return $q->select(['id_enrol' => 'MdlEnrol.id', 'tempo_acesso' => 'MdlEnrol.enrolperiod'])
                                ->where(['MdlEnrol.enrol' => 'manual']);
                        })
                        ->where(['MdlCourse.id' => $idCurso])
                        ->first();

                    $timeStart = new \DateTime();
                    $timeStart->setTime(0, 0, 0);
                    $timeStart = $timeStart->format('U');

                    $timeEnd = 0;
                    if ($curso->tempo_acesso > 0){
                        $timeEnd = new \DateTime();
                        $timeEnd->modify('+'.$curso->tempo_acesso.' second');
                        $timeEnd->setTime(23, 59, 59);
                        $timeEnd = $timeEnd->format('U');
                    }

                    $response = $this->MdlCourse->inserirInscricao([
                        'courseid' => $idCurso,
                        'userid' => $idUsuario,
                        'time_start' => $timeStart,
                        'time_end' => $timeEnd,
                        'alternative_host' => 713,
                        'proposta' => 0,
                        'produto' => $idProduto,
                        'pago' => false,
                        'enviar_email' => true
                    ]);

                    if (isset($response->sucesso) && $response->sucesso == true) {
                        $retorno = [
                            'sucesso' => true,
                            'mensagem' => $response->mensagem,
                            'link' => $linkAcesso
                        ];
                    } else {
                        $retorno['mensagem'] = __('Não foi possível realizar a inscrição');
                    }
                }else{
                    $retorno = [
                        'sucesso' => true,
                        'mensagem' => __('Usuário já matrículado'),
                        'link' => $linkAcesso
                    ];
                }
            }else{
                $retorno['mensagem'] = __('Produto não encontrado');
            }
        }

        $this->set(compact('retorno'));
    }

    /*
    * Função responsável por verificar se um usuário já está matrículado em um curso
    *
    * @param int $idCurso Id do curso
    * @param int $idUsuario Id do usuário
    * */
    private function verificaInscricao($idCurso, $idUsuario){
        $this->loadModel('WebService.MdlUserEnrolments');

        $enrol = $this->MdlUserEnrolments->find()
            ->select('MdlUserEnrolments.id')
            ->matching('MdlEnrol')
            ->where([
                'MdlUserEnrolments.userid' => $idUsuario,
                'MdlEnrol.courseid' => $idCurso
            ])->first();

        if(!is_null($enrol)){
            return true;
        }
        return false;
    }

}