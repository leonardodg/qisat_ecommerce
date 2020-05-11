<?php
/**
 * Created by PhpStorm.
 * User: inty.castillo
 * Date: 03/11/2017
 * Time: 10:57
 */

namespace FormaPagamentoCieloApi2\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Network\Http\Client;

class WscFormaPagamentoCieloApi2Controller extends WscController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Vendas.EcmVenda');
        $this->configuracao();
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    private function configuracao(){
        $this->loadModel('Configuracao.EcmConfig');
        $this->environment = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        $this->host = FormaPagamentoCieloApi2Controller::LINK;
    }

    public function consulta(){
        $retorno = ['sucesso' => false, 'mensagem' => __('Favor, informe uma recorrência ou uma transação')];

        $idVenda = $this->request->data('idVenda');
        if(!is_null($idVenda)) {
            $ecmVenda = $this->EcmVenda->get($idVenda, ['contain' => ['EcmTipoPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']]]]);
            $shortname = $this->venda->ecm_tipo_pagamento->ecm_forma_pagamento->ecm_alternative_host->shortname;
            unset($this->venda->ecm_tipo_pagamento);

            if(!is_null($ecmVenda)) {
                $this->headers = ['merchantid' =>
                    $this->EcmConfig->find()->where(['EcmConfig.nome' => 'merchant_id_checkout_'.strtolower($shortname)])->first()->valor
                ];

                $request = new Client();
                $response = $request->get($this->host .'/'. $this->headers['merchantid'] .'/'. $ecmVenda->id, [], ['type' => 'json', 'headers' => $this->headers]);
                $result = $response->json;

                if (is_array($result)) {
                    $status = self::getStatusVenda($result['payment_status']);
                    $ecmVenda->set('ecm_venda_status_id', $status);
                }

                if ($this->EcmVenda->save($ecmVenda))
                    $retorno = ['sucesso' => true, 'mensagem' => __('Venda atualizada'), 'venda' => $ecmVenda];
            }
        }

        $this->set(compact('retorno'));
    }

    public static function getStatusVenda($descricao){
        switch($descricao){
            case 1://Pendente
            case 4://Expirado
                return 1;//Andamento
            case 2://Pago
            case 7://Autorizado
                return 2;//Finalizada
            case 3://Negado
            case 5://Cancelado
            case 6://Não Finalizado
                return 5;//Cancelado
            case 8://Chargeback(estorno)
                return 3;//Estorno
        }
        return 1;//Andamento
        //return 4;//Boleto Não Pago
    }

    public function campainha(){
            $this->request->data('idVenda', $this->request->header('orderId')); // Verificar veracidade deste dado
        $this->consulta();
    }

    /**
     * @param $userid
     * @param $produtoid
     * @param $status
     *
     * Função em desuso
     */
    private function alterarAcessoMatricula($userid, $produtoid, $status){
        $this->loadModel('Produto.EcmProduto');
        $ecmProdutoMdlCourse = $this->EcmProduto->EcmProdutoMdlCourse->find()
            ->where(['ecm_produto_id' => $produtoid]);

        $this->loadModel('WebService.MdlUserEnrolments');
        foreach($ecmProdutoMdlCourse as $mdlCourse){
            $mdlUserEnrolments = $this->MdlUserEnrolments->find()
                ->matching('MdlEnrol', function($q)use($mdlCourse){
                    return $q->where(['courseid' => $mdlCourse->mdl_course_id]);
                })->where(['userid' => $userid])
                ->order(['timestart' => 'DESC'])
                ->first();

            if(!empty($mdlUserEnrolments) && $mdlUserEnrolments->status != $status){
                $mdlUserEnrolments->status = $status;
                $this->MdlUserEnrolments->save($mdlUserEnrolments);
            }
        }
    }

    /**
     * @param $recorrencia
     *
     * Função em desuso
     */
    private function enviarEmailErro($recorrencia){
        $email = new Email();
        $email->template('FormaPagamentoCieloApi2.emailErroAtualizacaoRecorrencia')->emailFormat('html');
        $email->subject('QiSat | Erro ao Atualizar Recorrência');

        $this->loadModel('Configuracao.EcmConfig');
        $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_sistema']])->first()->valor;
        $email->from([$noreply => 'QiSat - O Canal de e-Learning da Engenharia']);

        $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;
        $email->to([$supportemail => 'QiSat - O Canal de e-Learning da Engenharia']);

        $email->viewVars(['recorrencia' => $recorrencia]);
        $email->send();
    }
}