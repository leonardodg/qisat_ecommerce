<?php

namespace FormaPagamentoPagarMe\Controller;

use App\Auth\AESPasswordHasher;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Cake\Validation\Validator;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use Carrinho\Model\Entity\EcmVendaStatus;
use Exception;
use Firebase\JWT\JWT;
use FormaPagamento\Controller\FormaPagamentoAbstractController;
use PagarMe;
use Repasse\Model\Entity\EcmRepasse;

class FormaPagamentoPagarMeController extends AppController implements FormaPagamentoAbstractController
{
    use MailerAwareTrait;
    
    const STATUS = ['processing' => 2, 'authorized' => 1, 'paid' => 7, 'refunded' => 5, 
        'waiting_payment' => 2, 'pending_refund' => 5, 'refused' => 3];

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

    public function beforeFilter(Event $event)
    {
        $carrinho = $this->request->session()->read('carrinho');

        if(is_null($carrinho)){
            $this->Flash->error(__('Usuário não selecionado!'));
            return $this->redirect(['plugin' => false, 'controller' => 'usuario', 'action' => 'listar-usuario']);
        }

        $this->venda = $this->EcmVenda->find()->contain(['EcmOperadoraPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']], 'EcmTipoPagamento'])
            ->where(['ecm_carrinho_id'=>$carrinho->get('id')])->first();

        if(is_null($this->venda)){
            return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
        }

        return parent::beforeFilter($event);
    }

    private function configuracao(){
        $carrinho = $this->request->session()->read('carrinho');
        $ecmAlternativeHost = $this->EcmCarrinho->MdlUser->MdlUserEcmAlternativeHost
                                    ->EcmAlternativeHost->get($carrinho->ecm_alternative_host_id);
        $shortname = ($ecmAlternativeHost->shortname == 'AltoQi') ? 'altoqi' : 'qisat';
        $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'estabelecimento_'.$shortname.'_api_cielo'])->first()->valor;

        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        if($ambienteProducao->valor == 1){
            $this->api_key = 'ak_live_vUyWa7TC7Y8e8un6cOEKFBuSQmQNXA';
            $this->environment = 'prodution';
        }else{
            $this->api_key = 'ak_test_S6qra1TW3q5T7DCn1v5yn5tVG0ZTVW';
            $this->environment = 'sandbox';
        }
    }

    /**
     * Recorrencia não implementada
     */
    public function requisicao()
    {
        $retorno = [ 'sucesso' => false ];
        if($transacao = $this->criarTransacao()){
            $dados = $this->getDados();
            $errors = $this->validarDados($dados);

            if(!empty($errors)){
                $this->Flash->error(__(reset($errors)['_empty']));
                return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
            }

            $pagarme = new PagarMe\Client($this->api_key);
            try {
                $result = $pagarme->transactions()->create($dados);
            } catch (Exception $e) {
                $retorno['mensagem'] = __('Falha na Requisição: ');
                return $retorno;
            }

            if ($result->status) {
                if($transacao = $this->criarTransacao($transacao, $result)){
                    if ($result->status == 'authorized' || $result->status == 'paid') {

                        $retorno = [
                            'sucesso' => true,
                            'mensagem' => __('Pagamento efetuado com sucesso'),
                            'venda' => $this->venda->id
                        ];

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

                        $this->venda->set('ecm_venda_status', $vendaStatus);
                        $carrinho->set('status', EcmCarrinho::STATUS_FINALIZADO);

                        $this->EcmVenda->save($this->venda);
                        $this->EcmCarrinho->save($carrinho);

                        $this->enviarEmailCompra($carrinho);

                        if(!$this->request->is('post')){
                            $this->request->session()->delete('Flash');
                            $this->Flash->success(__($retorno['mensagem']));
                            return $this->redirect(['plugin' => false, 'controller' => 'MdlUser', 'action' => 'listar-usuario']);
                        }

                    } else if ($result->refuse_reason){
                        $retorno['mensagem'] = $this->getMensagemRetornoTransacao($result->refuse_reason);
                        $transacao->set('erro', $result->refuse_reason);
                        $this->EcmTransacao->save($transacao);
                    }

                }else{
                    $retorno['mensagem'] = __('Falha ao salvar transação!');
                }
            }else{
                if(isset($result->refuse_reason)) {
                    $retorno['mensagem'] = 'Erro ' . $result->refuse_reason;
                    $transacao->set('erro', $result->refuse_reason);
                    $this->EcmTransacao->save($transacao);
                }
            }
        }else{
            $retorno['mensagem'] = __('Falha na criação da transação!');
        }

        $linkEmail = "<a href='mailto:central@qisat.com.br'>central@qisat.com.br </a>";
        $mensagem = 'Ocorreu um erro ao iniciar uma transação com o pedido {0}, informe o suporte do site através do e-mail
                 '.$linkEmail.' ou entre em contato com a nossa central de vendas (48) 3332-5000.';

        if($this->request->is('post'))
            return $retorno;

        $this->Flash->error(__($mensagem, [$this->venda->id]), ['params' => ['hiddenClick' => false]]);
        $this->Flash->error(__($retorno['mensagem']));

        return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
    }

    public function retorno()
    {
    }

    public function criarTransacao($transacao = null, $retornoRequisicao = null){

        if(is_null($transacao)) {
            $transacao = $this->EcmTransacao->newEntity();
        }
        $carrinho = $this->request->session()->read('carrinho');

        $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
        $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
        $usuario = $carrinho->get('mdl_user');
        $transacao->set('parcela', 1);

        if(is_null($retornoRequisicao)) {

            $transacao->set('estabelecimento', $this->estabelecimento);
            $transacao->set('ecm_venda', $this->venda);
            $transacao->set('ecm_tipo_pagamento', $tipoPagamento);
            $transacao->set('ecm_operadora_pagamento', $operadoraPagamento);
            $transacao->set('mdl_user', $usuario);
            $transacao->set('ip', $this->request->clientIp());
            $transacao->set('ecm_transacao_status_id', 0);

            $transacao->set('valor', $carrinho->calcularTotal());
            $transacao->set('descricao', 'Cartão de Crédito Pagar Me');
            $transacao->set('data_envio', new \DateTime());
        
        }else{

            $transacao->set('data_retorno', new \DateTime());

            if(isset($retornoRequisicao->status))
                $transacao->set('ecm_transacao_status_id', self::STATUS[$retornoRequisicao->status]);
            
            if(isset($retornoRequisicao->id))
                $transacao->set('id_integracao', $retornoRequisicao->id);

            if(isset($retornoRequisicao->tid))
                $transacao->set('tid', $retornoRequisicao->tid);//O TID é o elo de ligação entre o pedido de compra da loja e a transação na Cielo

            if(isset($retornoRequisicao->nsu))
                $transacao->set('nsu', $retornoRequisicao->nsu);//Nº Sequência Autorização

            if(isset($retornoRequisicao->card) && isset($retornoRequisicao->card->fingerprint))
                $transacao->set('pan', $retornoRequisicao->card->fingerprint);//Hash do número do cartão do portador na Cielo

            if(isset($retornoRequisicao->authorization_code))
                $transacao->set('arp', $retornoRequisicao->authorization_code);//Código Autorização

            if(isset($retornoRequisicao->refuse_reason) && !is_null($retornoRequisicao->refuse_reason))
                $transacao->set('lr', $retornoRequisicao->refuse_reason);//Motivo Negação

            if(isset($retornoRequisicao->date_updated)){
                $transacao->set('capturar', 'true');
                $data_cobranca = \DateTime::createFromFormat("Y-m-d\TH:i:s.u\Z", $retornoRequisicao->date_updated);
                $transacao->set('data_cobranca', $data_cobranca);
            } else {
                $transacao->set('capturar', 'false');
            }
            
        }

        return $this->EcmTransacao->save($transacao);
    }

    private function getDados(){
        $carrinho = $this->request->session()->read('carrinho');
        $dataCartao = $this->request->data('cartao');
        if(is_null($dataCartao)){
            $dataCartao = JWT::decode(
                $this->request->session()->read('info')
                , Security::salt(), array('HS256'));
            $dataCartao = get_object_vars($dataCartao);
            $this->request->data['cartao'] = $dataCartao;
        }  
        if (array_key_exists('cartao', $dataCartao)){
            foreach ($dataCartao['cartao'] as $key => $value) 
                $dataCartao[$key] = $value;
        }

        $mdlUser = $this->MdlUser->get($carrinho->mdl_user_id, ['contain' => ['MdlUserDados', 'MdlUserEndereco']]);

        $phone_numbers = [];
        if(!empty($mdlUser->phone1))
            array_push($phone_numbers, $this->tratarPhone($mdlUser->phone1));
        if(!empty($mdlUser->phone2))
            array_push($phone_numbers, $this->tratarPhone($mdlUser->phone2));

        $documents = [[
            "type" => "cpf",
            "number" => preg_replace("/[^0-9]/", "", $mdlUser->mdl_user_dado->numero)
        ]];
        $customer = [
            "external_id" => strval($mdlUser->id),
            "name" => $mdlUser->firstname . ' ' . $mdlUser->lastname,
            "type" => $mdlUser->mdl_user_dado->tipousuario == "fisico" ? "individual" : "corporation",
            "country" => strtolower($mdlUser->country),
            "email" => $mdlUser->email,
            "documents" => $documents,
            "phone_numbers" => $phone_numbers
        ];

        $address = [
            "country" => strtolower($mdlUser->country),
            "state" => $mdlUser->mdl_user_endereco->state,
            "city" => $mdlUser->city,
            "neighborhood" => $mdlUser->mdl_user_endereco->district,
            "street" => $mdlUser->address,
            "street_number" => strval($mdlUser->mdl_user_endereco->number),
            "zipcode" => preg_replace("/[^0-9]/", "", $mdlUser->mdl_user_endereco->cep)
        ];
        $billing = [
            "name" => $mdlUser->firstname . ' ' . $mdlUser->lastname,
            "address" => $address
        ];

        $items = [];
        foreach ($carrinho->ecm_carrinho_item as $item) {
            array_push($items, [
                "id" => strval($item->ecm_produto->id),
                "title" => $item->ecm_produto->nome,
                "unit_price" => number_format($item->valor_produto_desconto,2,'',''),
                "quantity" => $item->quantidade,
                "tangible" => false // Se for um bem físico deve conter true 
            ]);
        }

        $installments = array_key_exists('valorParcelas', $this->request->data) ? $this->request->data['valorParcelas'] : $dataCartao['valorparcelas'];

        $dados = [
            'api_key' => $this->api_key,
            'amount' => number_format($carrinho->calcularTotal(),2,'',''), 
            'card_number' => str_replace(' ','',$dataCartao['numero']), 
            'card_cvv' => $dataCartao['codigo'], 
            'card_expiration_date' => $dataCartao['mesSelect'].($dataCartao['anoSelect']-2000),
            'card_holder_name' => $dataCartao['nome'], 
            'payment_method' => 'credit_card',
            'postback_url' => Router::url('/forma-pagamento-pagar-me/campainha', true),
            'installments' => --$installments, // Parcelas 
            'async' => false, 
            'customer' => $customer, 
            'billing' => $billing, 
            'items' => $items
        ];

        return $dados;
    }

    private function tratarPhone($phone){
        if (strpos($phone, '+') === false) 
            $phone = '55'.$phone;
        return '+'.preg_replace("/[^0-9]/", "", $phone);
    }

    private function validarDados($dados){
        $validator = new Validator();
        $validator
            ->requirePresence('card_number') 
                ->notEmpty('card_number', 'Número do cartão não informado')
                ->lengthBetween('card_number', [16, 16])
            ->requirePresence('card_cvv') 
                ->notEmpty('card_cvv', 'CCV do cartão não informado')
                ->lengthBetween('card_cvv', [3, 3])
            ->requirePresence('card_expiration_date') 
                ->notEmpty('card_expiration_date', 'Data de expiração do cartão não informado')
                ->lengthBetween('card_expiration_date', [4, 4])
            ->requirePresence('card_holder_name') 
                ->notEmpty('card_holder_name', 'Nome do titular do cartão não informado')
            ->requirePresence('installments')
                ->notEmpty('installments', 'Quantidade de parcelas não informadas')
            ->requirePresence('items')
                ->notEmpty('items', 'Items do carrinho não informados');

        $errors = $validator->errors($dados);
        if (!empty($errors)) 
            return $errors;
        
        $validator = new Validator();
        $validator
            ->requirePresence('country') 
                ->notEmpty('country', 'País do cliente não informado')
            ->requirePresence('email') 
                ->add('email', 'validFormat', ['rule' => 'email',  'message' => 'E-mail inválido'])
            ->requirePresence('documents') 
                ->notEmpty('documents', 'CPF/CNPJ do cliente não informado')
            ->requirePresence('phone_numbers') 
                ->notEmpty('phone_numbers', 'Telefone do cliente não informado');

        $errors = $validator->errors($dados['customer']);
        if (!empty($errors)) 
            return $errors;
        
        $validator = new Validator();
        $validator
            ->requirePresence('state') 
                ->notEmpty('state', 'Estado do cliente não informado')
            ->requirePresence('city') 
                ->notEmpty('city', 'Cidade do cliente não informado')
            ->requirePresence('neighborhood') 
                ->notEmpty('neighborhood', 'Bairro do cliente não informado')
            ->requirePresence('street') 
                ->notEmpty('street', 'Rua do cliente não informado')
            ->requirePresence('street_number') 
                ->notEmpty('street_number', 'Número da residência do cliente não informado')
            ->requirePresence('zipcode') 
                ->notEmpty('zipcode', 'CEP do cliente não informado');

        return $validator->errors($dados['billing']['address']);
    }

    public function enviarEmailCompra($carrinho){
        $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));

        $this->inserirRepasse($usuario, $carrinho);

        $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;

        $aesHash = new AESPasswordHasher();
        $senha = $aesHash->decrypt($usuario->get('password'));
        $paramsEmail = [
            'usuario' => $carrinho->get('mdl_user'),
            'senha' => $senha,
            'produtos' => $carrinho->get('ecm_carrinho_item'),
            'pedido' => $this->venda->id,
            'valor' => number_format($this->venda->valor_parcelas, 2, ',', ''),
            'parcelas' => $this->venda->numero_parcelas
        ];
        $params = [$fromEmail, $carrinho->get('mdl_user')->get('email'), $paramsEmail];
        $this->getMailer('FormaPagamentoPagarMe.FormaPagamentoPagarMe')->send('compraEfetuada', $params);

        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $params = [$fromEmail, $adminEmail, $paramsEmail];
        $this->getMailer('FormaPagamentoPagarMe.FormaPagamentoPagarMe')->send('compraEfetuada', $params);

        $this->request->session()->delete('compraConfirmada');
    }

    private function inserirRepasse($usuario, $carrinho){
        $this->loadModel('Repasse.EcmRepasse');

        $textoEmail = 'Prezado(a) <b> '.$usuario->firstname.' '.$usuario->lastname.'</b>,<br/>
                        <br/>Seu pedido <b>'.$this->venda->id.'</b> foi efetuado com sucesso no site <a href="www.qisat.com.br">www.qisat.com.br </a><br/>
                        <br/>Os cursos adquiridos foram:<br/>';

        foreach($carrinho->get('ecm_carrinho_item') as $produto){
            if($produto->get('status') == EcmCarrinhoItem::STATUS_ADICIONADO) {
                if (($produto->get('ecm_produto')->get('mdl_course')) > 1) {
                    foreach ($produto->get('ecm_produto')->get('mdl_course') as $curso) {
                        $textoEmail .= '<b>' . $curso->get('fullname') . '</b><br/>';
                    }
                } else {
                    $textoEmail .= '<b>' . $produto->get('ecm_produto')->get('nome') . '</b><br/>';
                }
            }
        }

        $textoEmail .= '<br/>Valor Total do Pedido: <b>'.$this->venda->numero_parcelas.'X de '.number_format($this->venda->valor_parcelas, 2, ',', '').'</b> <br/>
                        <br/>A forma de pagamento escolhida foi:<br/>
                        '.$this->venda->get('ecm_operadora_pagamento')->get('ecm_forma_pagamento')->get('nome').' <br/>
                        '.$this->venda->numero_parcelas;

        if($this->venda->numero_parcelas > 1)
            $textoEmail .= ' parcelas';
        else
            $textoEmail .= ' parcela';

        $textoEmail .= ' de '.number_format($this->venda->valor_parcelas, 2, ',', '').'<br/><br/>
                        Seus dados para acesso ao Ambiente Pessoal são:<br/>
                        Chave AltoQi/QiSat: <b>'.$usuario->username.'</b><br/><br/>
                        - A equipe QiSat entrará em contato com você por telefone e/ou e-mail em até 48 horas para confirmar
                        seus dados de compra e garantir que a sua escolha foi a melhor solução.<br>
                        - Durante o contato você poderá tirar possíveis dúvidas e agendar a data de início do curso.<br>
                        - Para acessar o curso faça login em <a href="www.qisat.com.br">www.qisat.com.br</a> e acesse a
                        área do aluno. No ambiente pessoal clique na opção “Meus Cursos”.<br><br/><br/>
                        Se deseja falar diretamente com a empresa, poderá fazê-lo através da Central de Inscrições.<br/>';

        $repasse = $this->EcmRepasse->newEntity();
        $repasse->set('status', EcmRepasse::STATUS_NAO_ATENDIDO);
        $repasse->set('assunto_email', 'QiSat| Confirmação de Pedido');
        $repasse->set('corpo_email', $textoEmail);
        $repasse->set('ecm_alternative_host_id', $carrinho->get('ecm_alternative_host_id'));

        $this->EcmRepasse->save($repasse);
    }

    public static function getMensagemRetornoTransacao($message){
        switch($message){
            case 'acquirer': 
                return 'Comprado';
            case 'antifraud': 
                return 'Informações inconsistentes';
            case 'no_acquirer': 
                return 'Nenhum comprador';
            case 'acquirer_timeout': 
                return 'Tempo limite para compra';
            default: // internal_error 
                 return 'Erro no servidor';
        }
    }
}