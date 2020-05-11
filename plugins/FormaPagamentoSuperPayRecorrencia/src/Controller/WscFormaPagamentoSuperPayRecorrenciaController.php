<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 03/11/2017
 * Time: 10:57
 */

namespace FormaPagamentoSuperPayRecorrencia\Controller;


use App\Controller\WscController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Network\Http\Client;
use Cake\Datasource\Exception\RecordNotFoundException;

class WscFormaPagamentoSuperPayRecorrenciaController extends WscController
{
    public function initialize()
    {
        parent::initialize();
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

        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        if($ambienteProducao->valor == 1){
            $this->environment = 'prodution';
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'estabelecimento_qisat_super_pay'])->first()->valor;
            $login =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'usuario_super_pay'])->first()->valor;
            $senha =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'senha_super_pay'])->first()->valor;
            $this->host = FormaPagamentoSuperPayRecorrenciaController::LINK_PRODUCAO;
            $this->url_transacao = 'https://superpay2.superpay.com.br/checkout/api/v2/transacao/';

        }else{
            $this->environment = 'sandbox';
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_estabelecimento_qisat_super_pay'])->first()->valor;
            $login = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_usuario_super_pay'])->first()->valor;
            $senha = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_senha_super_pay'])->first()->valor;
            $this->host = FormaPagamentoSuperPayRecorrenciaController::LINK_HOMOLOGACAO;
            $this->url_transacao = 'https://homologacao.superpay.com.br/checkout/api/v2/transacao/';
        }

        $this->auth = json_encode([ "login" => $login, "senha" => $senha ]);
    }

    public function campainha(){
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmVenda');
        
        $log = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'salve_log_campainha'])->first();
        $numeroRecorrencia = $this->request->data('numeroRecorrencia');
        $retorno = ['sucesso' => false, 'mensagem' => [] ];
        $datanow = new \DateTime();
        $http = new Client();
        $data = $this->request->data;
        $falha = false;
        $recorrencia = null;
        $transacao = null;
        
        if(isset($log) && $log->valor === 'true'){
            $arquivo = ROOT . DS . "SuperPay_Recorrencia" . date('d-m-Y') . ".json";
            $fp = fopen($arquivo, 'a+');
            fwrite($fp, "\n");
            fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
            fclose($fp);
        }

        try{

            $recorrencia = $this->EcmRecorrencia->get($numeroRecorrencia, [
                'contain' => ['MdlUser','EcmVenda']
            ]);

        }catch(RecordNotFoundException $e){
            $falha = true;
            $retorno['mensagem'][] = __('Recorrência  (ID:'.$numeroRecorrencia.') não localizada! (RecordNotFoundException)');
        }catch(\Exception $e){
            $falha = true;
            $retorno['mensagem'][] = __('Recorrência  (ID:'.$numeroRecorrencia.') não localizada! (Exception)');
        }

        if($recorrencia){
            $url = $this->host.'/'.$this->estabelecimento.'/'.$numeroRecorrencia;
    
            try {
                $response = $http->get($url, [], ['type' => 'json', 'headers' => array( 'usuario' => $this->auth )]);
                $result = $response->json;
            } catch (\Exception $e) {
                $falha = true;
                $retorno['mensagem'][] = __('Falha na Requisição - Consulta recorrência (Exception)');
            }

            if($response && $response->isOk()){

                if($result['recorrencia']){
                    $qtd = $result['recorrencia']['numeroCobrancaTotal'] - $result['recorrencia']['numeroCobrancaRestantes'];
                    $recorrencia->set('numero_cobranca_restantes', $result['recorrencia']['numeroCobrancaRestantes']);

                    if($result['recorrencia']['numeroCobrancaRestantes'] == 0)
                        $recorrencia->set('status', 0);

                    if($recorrencia->status && $qtd > 0){
                        $diff = $datanow->diff($recorrencia->data_primeira_cobranca);

                        if((($diff->format('%y') * 12) + $diff->format('%m')) > $recorrencia->quantidade_cobrancas)
                            $recorrencia->set('status', 0);
                    }

                    $this->EcmRecorrencia->save($recorrencia);
                    

                    try {
                        $idTransacao = $recorrencia->id.'00'.$qtd;
                        $resp = $http->get( $this->url_transacao.$this->estabelecimento.'/'.$idTransacao, [], ['type' => 'json', 'headers' => array( 'usuario' => $this->auth )] );
                        $resultTransacao = $resp->json; 
                        $data_cobranca = false;
                    } catch (\Exception $e) {
                        $falha = true;
                        $retorno['mensagem'][] = __('Falha na Requisição - Consulta transação (Exception)');
                    }
    
                    if($resp->isOk() || $resultTransacao){
    
                        $transacao = $this->EcmTransacao->newEntity();
                        $transacao->set('data_campainha', $datanow);
                        $transacao->set('id_integracao', $idTransacao );
                        $transacao->set('parcela', $qtd);
                        $transacao->set('estabelecimento', $this->estabelecimento);
                        $transacao->set('data_retorno', $datanow);
    
                        $transacao->set('ecm_recorrencia_id', $recorrencia->id);
                        $transacao->set('descricao', 'Campainha da Recorrência');
                        $transacao->set('mdl_user_id', $recorrencia['mdl_user_id']);
                        $transacao->set('valor', $recorrencia['valor']);
                        $transacao->set('ecm_tipo_pagamento_id', $recorrencia['ecm_tipo_pagamento_id']);
                        $transacao->set('ecm_operadora_pagamento_id', $recorrencia['ecm_operadora_pagamento_id']);
                        $transacao->set('ecm_venda_id', $recorrencia['ecm_venda_id']);
                        $transacao->set('ip', $recorrencia['ip']);
    
                        $status = (array_key_exists('statusTransacao', $resultTransacao)) ? $resultTransacao['statusTransacao'] : false;
                        $transacao->set('ecm_transacao_status_id', $resultTransacao['statusTransacao']);
    
                        if(array_key_exists('numeroTransacao', $resultTransacao) && ($status == '1' || $status == '3' || $status == '13')){
                            
                            if(array_key_exists('numeroComprovanteVenda', $resultTransacao))
                                $transacao->set('tid', $resultTransacao['numeroComprovanteVenda']);
                            
                            if(array_key_exists('autorizacao', $resultTransacao))
                                $transacao->set('arp', $resultTransacao['autorizacao']);
                            $transacao->set('lr', $resultTransacao['codigoTransacaoOperadora']);
    
                            if(array_key_exists('nsu', $resultTransacao))
                                $transacao->set('nsu', $resultTransacao['nsu']);
    
                            if(array_key_exists('mensagemVenda', $resultTransacao))
                                $transacao->set('erro', $resultTransacao['mensagemVenda']);
    
                            if(array_key_exists('dataAprovacaoOperadora', $resultTransacao)){
                                $data_cobranca = \DateTime::createFromFormat('d/m/Y', $resultTransacao['dataAprovacaoOperadora']);
                                $data_cobranca->setTime(0, 0, 0);
                                $transacao->set('data_cobranca', $data_cobranca);
                            }
    
                        }else if($status == '9' || $status == '5'){
                            if(array_key_exists('dataAprovacaoOperadora', $resultTransacao)){
                                $data_cobranca = \DateTime::createFromFormat('d/m/Y', $resultTransacao['dataAprovacaoOperadora']);
                                $data_cobranca->setTime(0, 0, 0);
                                $transacao->set('data_cobranca', $data_cobranca);
                            }
                        }
    
                        if($data_cobranca)
                            $transacao->set('data_envio', $data_cobranca);
                        else
                            $transacao->set('data_envio', $recorrencia->data_envio);
    
                        try {
                            if($status){
                                if($transacao = $this->EcmTransacao->save($transacao)){
                                    $transacao = $this->EcmTransacao->get($transacao->id, ['contain' =>['EcmTipoPagamento' => ['EcmFormaPagamento']] ]);
                                    $retorno['sucesso'] = true;
                                }else{
                                    $falha = true;
                                    $retorno['mensagem'][] =  __(' FALHA ao salvar Transação! ( ID:'.$idTransacao . ' ) ');
                                }
                            }
                        } catch (\Exception $e) {
                            $falha = true;
                            $retorno['mensagem'][] =  __(' FALHA ao salvar Transação:'.$idTransacao . ' Mensagem ('.$e->getMessage().')');
                        }
                    }
                }
        
            }else if($response && $response->code == 404){
                $falha = true;
                $retorno['mensagem'][] = __('Recorrência  (ID:'.$numeroRecorrencia.') não localizada! (404 - Retorno YAPAY)');
            }
        }else{
            $falha = true; 
            $retorno['mensagem'][] = __('Recorrência  (ID:'.$numeroRecorrencia.') não localizada!');
            $recorrencia = $numeroRecorrencia;
        }

        $this->enviarEmail($recorrencia, $transacao, $falha, $retorno['mensagem']);

        echo json_encode($retorno);die;
    }

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

    private function enviarEmail($recorrencia, $transacao, $falha, $mensagens){
        $this->loadModel('Configuracao.EcmConfig');

        $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_sistema']])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;
        $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;
        $cc = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_financeiro'])->first()->valor;

        $email = new Email();
        $email->template('FormaPagamentoSuperPayRecorrencia.emailErroAtualizacaoRecorrencia')->emailFormat('html');
        $email->subject('QiSat | Atualização de Pagamento - Recorrência');
        $email->from([$noreply => $fromEmailTitle]);
        $email->to([$supportemail => $fromEmailTitle]);
        $email->bcc([$cc]);

        $email->viewVars(['recorrencia' => $recorrencia, 'transacao' => $transacao, 'falha' => $falha, 'msg' => $mensagens]);
        $email->send();

    }
}