<?php
/**
 * Created by PhpStorm.
 * User: inty.castillo
 * Date: 10/12/2019
 * Time: 13:00
 */

namespace FormaPagamentoPagarMe\Controller;

use App\Auth\AESPasswordHasher;
use App\Controller\WscController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use Carrinho\Model\Entity\EcmVendaStatus;
use DateTime;
use PagarMe;

class WscFormaPagamentoPagarMeController extends WscController
{
    public function initialize()
    {
        $this->loadModel('MdlUser');
        $this->loadModel('Carrinho.EcmCarrinho');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Vendas.EcmVenda');

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
        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        if($ambienteProducao->valor == 1){
            $this->api_key = 'ak_live_vUyWa7TC7Y8e8un6cOEKFBuSQmQNXA';
            $this->environment = 'prodution';
        }else{
            $this->api_key = 'ak_test_S6qra1TW3q5T7DCn1v5yn5tVG0ZTVW';
            $this->environment = 'sandbox';
        }
    }

    public function consulta()
    { 
        $pagarMe = new PagarMe\Client($this->api_key);
        $result = (object)$pagarMe->transactions()->capture([
            'id' => $this->request->data['token'],
            'amount' => $this->request->data['amount']
        ]);

        $carrinho = $this->request->session()->read('carrinho');

        $user = $this->Auth->user();
        if(is_null($this->Auth->user())){
            $aes = new AESPasswordHasher();

            $cpf_cnpj = $this->formatCnpjCpf($result->customer->documents[0]->number); 
            
            $user = $this->MdlUser->find()
                ->matching('MdlUserDados', function ($q) use ($cpf_cnpj) {
                    return $q->where(['numero' => $cpf_cnpj]);
                })->contain(['MdlUserDados', 'MdlUserEndereco'])->first();

            if (is_null($user) || !$user) {
                $user = $this->MdlUser->find()->where([
                    'OR' => [
                        ['email'    => $result->customer->email],
                        ['username' => $result->customer->email]
                    ]
                ])->contain(['MdlUserDados', 'MdlUserEndereco'])->first();
            }

            $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_sistema']])->first()->valor;
            $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;
            $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;

            $email = new Email();
            $email->subject('QiSat | Atualização de Pagamento - transação');
            $email->from([$noreply => $fromEmailTitle]);
            $email->to([$supportemail => $fromEmailTitle]);
            
            if(is_null($user)){
                $user = $this->MdlUser->newEntity();
                $pos_espaco = strpos($result->customer->name, ' ');
                $user->firstname = substr($result->customer->name, 0, $pos_espaco);
                $user->lastname = substr($result->customer->name, $pos_espaco, strlen($result->customer->name));
                $user->username = $user->email = $result->customer->email;
                $user->phone1 = $result->customer->phone_numbers[0];
                $user->address = $result->billing->address->street;
                $user->city = $result->billing->address->city;
                $user->country = $result->billing->address->country;
                
                $user->timecreated = time();
                $user->lang = "pt_br";
                $user->timezone = "America/Sao_Paulo";
                $user->mnethostid = $user->confirmed = 1;
                
                $user->auth = "aesauth";
                $user->password = $aes->hash($this->request->data['password']);

                if($this->MdlUser->save($user)){
                    $dados = $this->MdlUser->MdlUserDados->newEntity();
                    $dados->mdl_user_id = $user->id;
                    $dados->numero = $cpf_cnpj; 
                    $dados->tipousuario = $result->customer->type == 'individual' ? 'fisico' : 'juridico';
                    $this->MdlUser->MdlUserDados->save($dados);
                    $user->mdl_user_dados = $dados;
                    $user->mdl_user_dados_id = $dados->id;
                    
                    $endereco = $this->MdlUser->MdlUserEndereco->newEntity();
                    $endereco->id = $user->id;
                    $endereco->number = $result->billing->address->street_number;
                    $endereco->complement = $result->billing->address->complementary;
                    $endereco->district = $result->billing->address->neighborhood;
                    $endereco->state = $result->billing->address->state;
                    $endereco->cep = $result->billing->address->zipcode;
                    $this->MdlUser->MdlUserEndereco->save($endereco);
                    $user->mdl_user_endereco = $endereco;
                    $user->mdl_user_endereco_id = $endereco->id;
                } else {
                    $email->template('FormaPagamentoPagarMe.emailErroUsuario')->emailFormat('html');
                    $email->viewVars(['result' => $result]);
                    $email->send();
                }
            } else {
                $email->template('FormaPagamentoPagarMe.emailVerificarUsuario')->emailFormat('html');
                $email->viewVars(['result' => $result]);
                $email->send();
            }

            $carrinho->set('mdl_user_id', $user->id);
            $carrinho->set('mdl_user', $user);

            $this->request->session()->write('carrinho', $carrinho);
            $this->EcmCarrinho->save($carrinho);

            if(!isset($password))
                $password = $aes->decrypt($user->password);
        } else {
            $user = $this->MdlUser->newEntity($user);
        }

        $controller = new FormaPagamentoPagarMeController();
        $controller->venda = $this->EcmVenda->find()->contain(['EcmOperadoraPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']], 'EcmTipoPagamento'])
            ->where(['ecm_carrinho_id'=>$carrinho->get('id')])->first();

        if(is_null($controller->venda)){
            $operadora = null;
            try {
                $operadora = $this->EcmVenda->EcmOperadoraPagamento->find()
                    ->where(['EcmOperadoraPagamento.dataname' => $result->card_brand])
                    ->contain(['EcmFormaPagamento'])
                    ->matching('EcmFormaPagamento', function($q){
                        return $q->where(['controller' => 'PagarMe']);
                    })->first();
            } catch (RecordNotFoundException $e) {
                return ['sucesso' => false, 'mensagem' => __('Operadora não encontrada')];
            }

            $tipoPagamento = null;
            try {
                $tipoPagamento = $this->EcmVenda->EcmTipoPagamento->find()
                    ->contain(['EcmFormaPagamento'])
                    ->matching('EcmFormaPagamento', function($q){
                        return $q->where(['controller' => 'PagarMe']);
                    })->first();
            } catch (RecordNotFoundException $e) {
                return ['sucesso' => false, 'mensagem' => __('Tipo de pagamento não encontrado')];
            }

            $parcelas = $result->installments;
            $valorParcelas = $carrinho->calcularParcela($parcelas);
            $vendaStatus = $this->EcmVenda->EcmVendaStatus->find('all')
                ->where(['status' => EcmVendaStatus::STATUS_ANDAMENTO])->first();

            $venda = $this->EcmCarrinho->EcmVenda->newEntity();
            $venda->set('valor_parcelas', $valorParcelas);
            $venda->set('numero_parcelas', $parcelas);
            $venda->set('ecm_venda_status', $vendaStatus);
            $venda->set('mdl_user_id', $carrinho->get('mdl_user_id'));
            $venda->set('ecm_operadora_pagamento', $operadora);
            $venda->set('ecm_tipo_pagamento', $tipoPagamento);
            $venda->set('ecm_carrinho_id', $carrinho->get('id'));

            $controller->venda = $this->EcmVenda->save($venda);
        }

        $this->createToken($user->id, $user->username, true);
        $retorno = [
            'sucesso' => true,
            'mensagem' => __('Pagamento efetuado com sucesso'),
            'venda' => $controller->venda->id,
            'usuario' => $user
        ];

        if(is_null($carrinho->get('mdl_user'))){
            $user = $this->MdlUser->get($this->Auth->user('id'), ['contain' => ['MdlUserDados', 'MdlUserEndereco']]);
            $carrinho->set('mdl_user', $user);
        }

        $transacao = $controller->criarTransacao();
        if($transacao = $controller->criarTransacao($transacao, $result)){
            if ($result->status == 'authorized' || $result->status == 'paid') {
                $carrinho = $this->request->session()->read('carrinho');
                if ($carrinho->checkItensStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO)) {
                    $carrinhoNovo = $carrinho->novaEntidadeComValores();
                    $this->EcmCarrinho->save($carrinhoNovo);

                    $carrinhoNovo->addItensPorStatus($carrinho->get('ecm_carrinho_item'), EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);
                    $carrinho->removeItensPorStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);

                    $this->EcmCarrinho->save($carrinhoNovo);
                    $this->request->session()->write('carrinho', $carrinhoNovo);
                }

                $vendaStatus = $this->EcmVenda->EcmVendaStatus->find()
                                    ->where(['status' => EcmVendaStatus::STATUS_FINALIZADO])->first();

                $controller->venda->set('ecm_venda_status', $vendaStatus);
                $carrinho->set('status', EcmCarrinho::STATUS_FINALIZADO);

                $this->EcmVenda->save($controller->venda);
                $this->EcmCarrinho->save($carrinho);
                $this->request->session()->write('carrinho', $carrinho);

                $controller->enviarEmailCompra($carrinho);

            } else if ($result->refuse_reason){
                $retorno['mensagem'] = $controller->getMensagemRetornoTransacao($result->refuse_reason);
                $transacao->set('erro', $result->refuse_reason);
                $this->EcmTransacao->save($transacao);
            }

        }else{
            $retorno['sucesso'] = false;
            $retorno['mensagem'] = __('Falha ao salvar transação!');
        }
        $this->set(compact('retorno'));
    }

    public function campainha(){
        $pagarme = new PagarMe\Client($this->api_key);

        $requestBody = file_get_contents("php://input"); 
        $signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];

        $isValidPostback = $pagarme->postbacks()->validate($requestBody, $signature);
        
        if ($isValidPostback) {
            $data = $this->request->data;
            $transacao = $this->EcmTransacao->find()->where(['id_integracao' => $data['id']])->first();

            if(!is_null($transacao)){
                $transacao->ecm_transacao_status_id = FormaPagamentoPagarMeController::STATUS[$data['current_status']];
                $transacao->data_campainha = new DateTime();
    
                $this->EcmTransacao->save($transacao);
                $this->enviarEmail($transacao->mdl_user, $data['transaction']);
            }
        }
    }

    private function enviarEmail($mdlUser, $result){
        $noreply = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_sistema']])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;
        $supportemail = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'supportemail']])->first()->valor;
        $email_financeiro = $this->EcmConfig->find('all', ['conditions' => ['nome' => 'email_financeiro']])->first()->valor;

        $email = new Email();
        $email->template('FormaPagamentoPagarMe.emailAtualizacaoTransacao')->emailFormat('html');
        $email->subject('QiSat | Atualização de Pagamento - transação');
        $email->from([$noreply => $fromEmailTitle]);
        $email->to([$supportemail => $fromEmailTitle, $email_financeiro => $fromEmailTitle]);

        $email->viewVars(['mdlUser' => $mdlUser, 'result' => $result]);
        $email->send();
    }

    function formatCnpjCpf($value) {
        $cnpj_cpf = preg_replace("/\D/", '', $value);
        
        if (strlen($cnpj_cpf) === 11) 
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }
}