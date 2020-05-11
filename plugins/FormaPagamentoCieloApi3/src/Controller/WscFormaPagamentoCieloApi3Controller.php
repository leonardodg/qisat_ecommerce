<?php
/**
 * Created by PhpStorm.
 * User: inty.castillo
 * Date: 03/11/2017
 * Time: 10:57
 */

namespace FormaPagamentoCieloApi3\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Network\Http\Client;
use Carrinho\Model\Entity\EcmTransacao;

class WscFormaPagamentoCieloApi3Controller extends WscController
{

    const CONSULTA_HOMOLOGACAO = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/1/sales/';
    const CONSULTA_PRODUCAO = 'https://apiquery.cieloecommerce.cielo.com.br/1/sales/';

    public function initialize()
    {
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmRecorrencia');

        parent::initialize();
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    private function configuracao($shortname){
        $this->loadModel('Configuracao.EcmConfig');
        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        if($ambienteProducao->valor == 1){
            $this->host = self::CONSULTA_PRODUCAO;
            $this->headers = ['MerchantId' =>
                $this->EcmConfig->find()->where(['EcmConfig.nome' => 'merchant_id_api_'.strtolower($shortname)])->first()->valor,
                              'MerchantKey' =>
                $this->EcmConfig->find()->where(['EcmConfig.nome' => 'merchant_key_api_'.strtolower($shortname)])->first()->valor
            ];
        }else{
            $this->host = self::CONSULTA_HOMOLOGACAO;
            $this->headers = ['MerchantId' =>
                $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_merchant_id_api_'.strtolower($shortname)])->first()->valor,
                              'MerchantKey' =>
                $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_merchant_key_api_'.strtolower($shortname)])->first()->valor
            ];
        }
    }

    public function consulta(){
        $retorno = [
            'sucesso'     => false,
            'mensagem'    => __('Favor, informe uma recorrência ou uma transação'),
            'recorrencia' => null,
            'transacao'   => null
        ];

        $idRecorrencia = $this->request->data('idRecorrencia');
        $recurrentPaymentId = $this->request->data('RecurrentPaymentId');
        if(!is_null($idRecorrencia) || !is_null($recurrentPaymentId)) {
            if(!is_null($idRecorrencia))
                $ecmRecorrencia = $this->EcmRecorrencia->get($idRecorrencia, ['contain' => ['MdlUser', 'EcmVenda', 'EcmTipoPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']]]]);
            else
                $ecmRecorrencia = $this->EcmRecorrencia->find('all', ['contain' => ['MdlUser', 'EcmVenda', 'EcmTipoPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']]]])
                    ->where(['id_integracao' => $recurrentPaymentId])->orderDesc('EcmRecorrencia.id')->first();

            if(!is_null($ecmRecorrencia)) {
                $shortname = $ecmRecorrencia->ecm_tipo_pagamento->ecm_forma_pagamento->ecm_alternative_host->shortname;
                unset($ecmRecorrencia->ecm_tipo_pagamento->ecm_forma_pagamento->ecm_alternative_host);
                $this->configuracao($shortname);

                $host = str_replace('sales', 'RecurrentPayment', $this->host) . $ecmRecorrencia->id_integracao;

                $request = new Client();
                $response = $request->get($host, [], ['type' => 'json', 'headers' => $this->headers]);
                $result = $response->json;

                //$status = 4;
                $amount = 0;
                $countTransacao = 0;
                $ecmTransacao = null;
                foreach($result['RecurrentPayment']['RecurrentTransactions'] as $transacao){
                    $request = new Client();
                    $response2 = $request->get($this->host . $transacao['PaymentId'], [], ['type' => 'json', 'headers' => $this->headers]);
                    $result2 = $response2->json;

                    $ecmTransacao = $this->EcmTransacao->find()
                        ->where(['id_integracao' => $transacao['PaymentId']])->first();
                    if(is_null($ecmTransacao)) {
                        $ecmTransacao = $this->EcmTransacao->newEntity();
                        $ecmTransacao->id_integracao = $transacao['PaymentId'];
                        $ecmTransacao->mdl_user_id   = $ecmRecorrencia->mdl_user_id;

                        $amount = $result2['Payment']['Amount'];
                        $ecmTransacao->valor = substr($amount, 0, -2) . '.' . substr($amount, -2);

                        $ecmTransacao->numero_parcela             = $transacao['PaymentNumber']+1;
                        $ecmTransacao->ecm_tipo_pagamento_id      = $ecmRecorrencia->ecm_tipo_pagamento_id;
                        $ecmTransacao->ecm_operadora_pagamento_id = $ecmRecorrencia->ecm_operadora_pagamento_id;
                        $ecmTransacao->ecm_venda_id   = $ecmRecorrencia->ecm_venda_id;
                        $ecmTransacao->data_envio     = $result2['Payment']['ReceivedDate'];
                        $ecmTransacao->data_campainha = new \DateTime();

                        $ecmTransacao->tid = $result2['Payment']['Tid'];
                        $ecmTransacao->nsu = $result2['Payment']['ProofOfSale'];
                        //$transacao->set('pan', $retornoRequisicao['']);
                        $ecmTransacao->arp = $result2['Payment']['AuthorizationCode'];
                        $ecmTransacao->url = $result2['Payment']['Links'][0]['Href'];

                        if($result2['Payment']['Status'] > 2)
                            $ecmTransacao->lr = $ecmTransacao->erro = $result2['Payment']['Status'];
                        else
                            $countTransacao++;

                        $ecmTransacao->teste = $this->host == self::CONSULTA_HOMOLOGACAO ? 'true' : 'false';
                        $ecmTransacao->ip    = $this->request->clientIp();
                    }

                    if (array_key_exists('CapturedDate', $result2['Payment'])) {
                        $ecmTransacao->set('capturar', 'true');
                        $ecmTransacao->set('data_retorno', $result2['Payment']['CapturedDate']);
                    }
                    $status = self::getStatusTransacao($result2['Payment']['Status']);
                    $ecmTransacao->set('ecm_transacao_status_id', $status);

                    $this->EcmTransacao->save($ecmTransacao);
                }

                //$ecmRecorrencia->status           = self::getStatusRecorrencia($result['RecurrentPayment']['Status']);
                //$ecmRecorrencia->transacao_status = $status;
                if($amount)
                    $ecmRecorrencia->valor = $amount;

                $ecmRecorrencia->data_envio   = $result['RecurrentPayment']['StartDate'];
                $ecmRecorrencia->data_retorno = $result['RecurrentPayment']['EndDate'];

                $ecmRecorrencia->numero_cobranca_restantes  = $ecmRecorrencia->numero_cobranca_total - $countTransacao;
                $ecmRecorrencia->data_campainha             = $result['RecurrentPayment']['NextRecurrency'];

                if ($this->EcmRecorrencia->save($ecmRecorrencia)){
                    $ecmTransacao->ecm_tipo_pagamento = $ecmRecorrencia->ecm_tipo_pagamento;
                    $retorno = [
                        'sucesso'     => true,
                        'mensagem'    => __('Recorrência atualizada'),
                        'recorrencia' => $ecmRecorrencia,
                        'transacao'   => $ecmTransacao
                    ];
                }
            }
        }

        $idTransacao = $this->request->data('idTransacao');
        $paymentId = $this->request->data('PaymentId');
        if(!isset($ecmRecorrencia) && (!is_null($idTransacao) || !is_null($paymentId))) {
            if(!is_null($idTransacao))
                $ecmTransacao = $this->EcmTransacao->get($idTransacao, ['contain' => ['EcmTipoPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']]]]);
            else
                $ecmTransacao = $this->EcmTransacao->find('all', ['contain' => ['EcmTipoPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']]]])
                    ->where(['id_integracao' => $paymentId])->orderDesc('EcmTransacao.id')->first();

            if(!is_null($ecmTransacao)) {
                $shortname = $ecmTransacao->ecm_tipo_pagamento->ecm_forma_pagamento->ecm_alternative_host->shortname;
                unset($ecmTransacao->ecm_tipo_pagamento->ecm_forma_pagamento->ecm_alternative_host);
                $this->configuracao($shortname);

                $request = new Client();
                $response = $request->get($this->host . $ecmTransacao->id_integracao, [], ['type' => 'json', 'headers' => $this->headers]);
                $result = $response->json;

                if (array_key_exists('CapturedDate', $result['Payment'])) {
                    $ecmTransacao->set('capturar', 'true');
                    $ecmTransacao->set('data_retorno', $result['Payment']['CapturedDate']);
                }
                $ecmTransacao->data_campainha = new \DateTime();
                $status = self::getStatusTransacao($result['Payment']['Status']);
                $ecmTransacao->set('ecm_transacao_status_id', $status);

                if ($this->EcmTransacao->save($ecmTransacao)){
                    $retorno = [
                        'sucesso'     => true,
                        'mensagem'    => __('Transação atualizada'),
                        'recorrencia' => null,
                        'transacao'   => $ecmTransacao
                    ];
                }
            }
        }

        $this->set(compact('retorno'));
        //$this->set('_serialize', ['retorno']);
    }

    /**
     * @param $descricao
     * @return int
     */
    public static function getStatusTransacao($descricao){
        switch($descricao){
            case 1: //return 'Pagamento apto a ser capturado ou definido como pago';
            case 12: //return 'Aguardando Status de instituição financeira';
            case 20: //return 'Recorrência agendada';
                return 1;
            case 0: //return 'Aguardando atualização de status';
                return 2;
            case 10: //return 'Pagamento cancelado';
            case 11: //return 'Pagamento cancelado após 23:59 do dia de autorização';
            case 13: //return 'Pagamento cancelado por falha no processamento ou por ação do AF';
                return 3;
            case 3: //return 'Pagamento negado por Autorizador';
                return 6;
            case 2: //return 'Pagamento confirmado e finalizado';
                return 7;
        }
        //return 'Não Autorizada';
        return 4;
    }

    /**
     * Função em desuso
     *
     * @param $descricao
     * @return int
     */
    public static function getStatusRecorrencia($descricao){
        switch($descricao){
            case 1: //return 'Ativo';
                return 35;
            case 2: //return 'Finalizado';
                return 1;
            case 3: //return 'Desativada pelo Lojista';
                return 50;
            case 5: //return 'Desativada por cartão de crédito vencido';
                return 103;
        }
        //return 'Desativada por numero de retentativas';
        return 104;
    }

    public function campainha(){
        $recurrentPaymentId = $this->request->header('RecurrentPaymentId');
        $paymentId = $this->request->header('PaymentId');
        $falha = false;
        $mensagens = [];
        if(!is_null($recurrentPaymentId)){
            $this->request->data('RecurrentPaymentId', $recurrentPaymentId);
        }else{
            $this->request->data('PaymentId', $paymentId);
        }
        try{
            $this->consulta();
        }catch(\Exception $e){
            $falha = true;
            if(!is_null($recurrentPaymentId))
                $mensagens[] = __('Recorrência (ID:'.$recurrentPaymentId.') não localizada! (Exception: '.$e->getMessage().')');
            else
                $mensagens[] = __('Transação (ID:'.$paymentId.') não localizada! (Exception: '.$e->getMessage().')');
        }

        $retorno = $this->viewVars['retorno'];
        $this->enviarEmail($retorno['recorrencia'], $retorno['transacao'], $falha, $mensagens);
    }

    private function enviarEmail($recorrencia, $transacao, $falha, $mensagens){
        $this->loadModel('Configuracao.EcmConfig');

        $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_sistema']])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;
        $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;
        $email_financeiro = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_financeiro']])->first()->valor;

        $email = new Email();
        $email->template('FormaPagamentoSuperPayRecorrencia.emailErroAtualizacaoRecorrencia')->emailFormat('html');
        $email->subject('QiSat | Atualização de Pagamento - Recorrência');
        $email->from([$noreply => $fromEmailTitle]);
        $email->to([$supportemail => $fromEmailTitle, $email_financeiro => $fromEmailTitle]);

        $email->viewVars(['recorrencia' => $recorrencia, 'transacao' => $transacao, 'falha' => $falha, 'msg' => $mensagens]);
        $email->send();

    }

    /**
     * Função em desuso
     *
     * @param $userid
     * @param $produtoid
     * @param $status
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
}