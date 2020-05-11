<?php

namespace FormaPagamentoSuperPayRecorrencia\Controller;

use App\Auth\AESPasswordHasher;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
use Cake\Utility\Security;
use Cake\Datasource\Exception\RecordNotFoundException;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use Carrinho\Model\Entity\EcmVendaStatus;
use Firebase\JWT\JWT;
use FormaPagamento\Controller\FormaPagamentoAbstractController;
use Repasse\Model\Entity\EcmRepasse;
use Cake\Network\Http\Client;
use App\Controller\WscController;

class FormaPagamentoSuperPayRecorrenciaController extends AppController implements FormaPagamentoAbstractController
{
    use MailerAwareTrait;
    // use Client;
    const LINK_PRODUCAO = 'https://superpay2.superpay.com.br/checkout/api/v2/recorrencia';
    const LINK_HOMOLOGACAO = 'https://homologacao.superpay.com.br/checkout/api/v2/recorrencia';

    private $venda = null;
    public function initialize()
    {
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmVenda');
        $this->loadModel('Carrinho.EcmCarrinho');
        $this->loadModel('MdlUser');

        parent::initialize();
        $this->configuracao();
    }

    public function beforeFilter(Event $event)
    {
        $carrinho = $this->request->session()->read('carrinho');

        if(!is_null($carrinho)){
            $this->venda = $this->EcmVenda->find()->contain(['EcmOperadoraPagamento' => ['EcmFormaPagamento'], 'EcmTipoPagamento'])
            ->where(['ecm_carrinho_id'=>$carrinho->get('id')])->first();
        }

        return parent::beforeFilter($event);
    }

    private function configuracao(){
        $this->loadModel('Configuracao.EcmConfig');
        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        if($ambienteProducao->valor == 1){
            $this->environment = 'prodution';
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'estabelecimento_qisat_super_pay'])->first()->valor;
            $login =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'usuario_super_pay'])->first()->valor;
            $senha =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'senha_super_pay'])->first()->valor;
            $this->host = self::LINK_PRODUCAO;
        }else{
            $this->environment = 'sandbox';
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_estabelecimento_qisat_super_pay'])->first()->valor;
            $login = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_usuario_super_pay'])->first()->valor;
            $senha = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_senha_super_pay'])->first()->valor;
            $this->host = self::LINK_HOMOLOGACAO;
        }

        $this->auth = json_encode([ "login" => $login, "senha" => $senha ]);
    }


    public function requisicao()
    {
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Entidade.EcmAlternativeHost');

        $retorno = [ 'sucesso' => false ];
        $http = new Client();
        $carrinho = $this->request->session()->read('carrinho');

        $numeroParcelas = $this->venda->get('numero_parcelas');
        $valor_parcelas = $carrinho->calcularParcela($numeroParcelas);

        if($recorrencia = $this->setRecorrencia()){
            $dados = $this->getDados($recorrencia);

            try {

                $response = $http->post( $this->host, json_encode($dados), ['type' => 'json', 'headers' => array( 'usuario' => $this->auth )] );
                $result = $response->json;
                $recorrencia = $this->setRecorrencia($result, $recorrencia->get('id'));

            } catch (\Exception $e) {
                $retorno['mensagem'] = __('Falha na Requisição!');
                return $retorno;
            }

            if(isset($response) && $response->isOk()){
                if($result['recorrencia']){
                    if($result['recorrencia']['statusTransacao'] == 1 || $result['recorrencia']['statusTransacao'] == 2) {
                        
                        $retorno = [
                            'sucesso' => true,
                        	'mensagem' => __('Pagemento efetuado com sucesso'),
                            'venda' => $this->venda->id
                        ];

                        if($recorrencia->valor !== $this->venda->valor_parcelas){
                            if($this->editValor(number_format($this->venda->valor_parcelas, 2, '', ''), $recorrencia)){
                                $recorrencia->set('valor', $this->venda->valor_parcelas);
                                $this->EcmRecorrencia->save($recorrencia);
                            }
                        }

                        if($carrinho->checkItensStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO)){
                            $carrinhoNovo = $carrinho->novaEntidadeComValores();
                            $this->EcmCarrinho->save($carrinhoNovo);

                            $carrinhoNovo->addItensPorStatus($carrinho->get('ecm_carrinho_item'), EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);
                            $carrinho->removeItensPorStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);

                            $this->EcmCarrinho->save($carrinhoNovo);
                            $this->request->session()->write('carrinho', $carrinhoNovo);
                        }

                        $vendaStatus = $this->EcmVenda->EcmVendaStatus->find()
                                                                      ->where(['status' => EcmVendaStatus::STATUS_FINALIZADO])
                                                                      ->first();

                        $this->venda->set('ecm_venda_status', $vendaStatus);
                        $carrinho->set('status', EcmCarrinho::STATUS_FINALIZADO);

                        $this->EcmVenda->save($this->venda);
                        $this->EcmCarrinho->save($carrinho);

                        /*$this->loadModel('WebService.MdlCourse');
                        foreach($carrinho->get('ecm_carrinho_item') as $item){
                            $this->MdlCourse->matricular($carrinho->get('mdl_user_id'), $item->get('ecm_produto_id'), true);
                        }*/

                        if($mail = $this->enviarEmailCompra($carrinho)){
                            $this->loadModel('MdlUser');
                            $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));
                            $mensagem = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                            $this->inserirRepasse($usuario, $carrinho,$mensagem);
                        }
                
                        
                    }else{
                        
                        if( $result['recorrencia']['mensagemVenda']){
                            $retorno['mensagem'] =  $result['recorrencia']['mensagemVenda'];
                        }

                        try {
                            $response = $http->post( $this->host.'/cancelar/'.$this->estabelecimento.'/'.$recorrencia->get('id'), [], ['type' => 'json', 'headers' => array( 'usuario' => $this->auth )] );
                            $result = $response->json;  
                        } catch (\Exception $e) {
                            $retorno['error'] = __('Falha na Requisição - Cancelar!');
                        }

                        $recorrencia->set('status', '0');
                        $this->EcmRecorrencia->save($recorrencia);
                    }
                }
                
            }else{
                $retorno['dados'] = $dados;
                $retorno['host'] = $this->host;
                $retorno['headers'] = array( 'usuario' => $this->auth );

                $recorrencia->set('status', '0');
                $recorrencia->set('mensagem_venda', __('Falha na Requisição - Criar Recorrência'));
                $retorno['mensagem'] =   __('Falha na Requisição - Criar Recorrência');
                $this->EcmRecorrencia->save($recorrencia);
            }
        }

        $referer = substr($this->referer(), 0, strpos($this->referer(), "/", 8)+1);
        $link_ecommerce = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'link_ecommerce'])->first()->valor;

        if (strpos($referer, $link_ecommerce) !== false) {
            if(!$retorno['sucesso'])
                return $this->redirect(['plugin' => 'Carrinho','controller' => '','action' => 'confirmardados', 'error' => 'true' ]);

            $usuario = $carrinho->get('mdl_user');
            $venda = $this->venda;

            $this->set(compact('usuario','venda'));
            $this->set('_serialize', ['usuario','venda']);

            $this->render("confirmarcompra");
        }else 
            return $retorno;
    }

    public function retorno()
    {
        return;
    }


    /*
    
    
        curl 'https://superpay2.superpay.com.br/checkout/api/v2/recorrencia/1429340321334/10275' 
            -H 'content-encoding: UTF-8' -H 'content-type: application/json' 
            -H 'usuario: { "login":"MNTECNOLOGIA","senha":"restMN1041"}'

    
    */
    public function cancelar()
    {
                
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');

        $retorno = ['sucesso' => false ];
        $http = new Client();
        $id = $this->request->data('id');

        if($this->request->is('post') and !is_null($id)){

            try {

                $recorrencia = $this->EcmRecorrencia->find()->where(['id' => $id ])->first();
                $response = $http->post( $this->host.'/cancelar/'.$this->estabelecimento.'/'.$id , [], ['type' => 'json', 'headers' => array( 'usuario' => $this->auth )] );
                $result = $response->json;  
    
            } catch (\Exception $e) {
                $retorno['mensagem'] = __('Falha na Requisição Cancelar!');
            } catch (RecordNotFoundException $e) {
                $retorno['mensagem'] = __('Recorreência não Encontrada!');
            }

            if($response->isOk()){
                $recorrencia->set('status', '0');
                $retorno['sucesso'] = true;
    
                if($result['recorrencia'] && $result['recorrencia']['numeroRecorrencia'] == $recorrencia->get('id')){
                    $recorrencia->set('mensagem_venda', $result['recorrencia']['mensagem']);
                    $retorno['mensagem'] = $result['recorrencia']['mensagem'];
                }else if($result['erro'] && $result['erro']['mensagem']){
                    $recorrencia->set('mensagem_venda', $result['erro']['mensagem']);
                    $retorno['mensagem'] = $result['erro']['mensagem'];
                }
    
                $this->EcmRecorrencia->save($recorrencia);
            }else if($response->code == 404 && $result['numeroTransacao'] == $recorrencia->get('id')){
    
                $recorrencia->set('status', '0');
                $retorno['sucesso'] = true;
    
                if($result['erro'] && $result['erro']['mensagem']){
                    $recorrencia->set('mensagem_venda', $result['erro']['mensagem']);
                    $retorno['mensagem'] = $result['erro']['mensagem'];
                }
    
                $this->EcmRecorrencia->save($recorrencia);
            }

        }

        $this->set(compact('retorno'));
    }

    /*
        @paramentrs $estabelecimento => numero do estabelecimento
                    $id => código de identificação da recorrencia
    */
    public function consulta($id, $estabelecimento = null)
    {
        $est = $estabelecimento ?: $this->estabelecimento;
        $url = $this->host.'/'.$est.'/'.$id;
        $http = new Client();
        $response = $http->get( $url, [], ['type' => 'json', 'headers' => array( 'usuario' => $this->auth )] );
        return ($response->isOk()) ? [ 'sucesso' => true, 'returno' => $response->json] : [ 'sucesso' => false, 'returno' => $response->json];
    }

    /*
     * Função responsável em editar valor da recorrencia 
     * 
     * Utlizando link versão 3 yapay
     * obs.: esta versão alterar forma de autenticação da requisição
     * 
    * @param  $recorrencia => EcmRecorrecia
    *         $valor => valor para atualizar ( Formado sem ponto ou virgula )
    *
    * @return boolean true or false
    */
    public function editValor($valor, $recorrencia)
    {
        
        $link_producao_v3 = 'https://gateway.yapay.com.br/checkout/api/v3/recorrencia';
        $link_homologacao_v3 = 'https://sandbox.gateway.yapay.com.br/checkout/api/v3/recorrencia';

        if($this->environment == 'prodution'){
            $host = $link_producao_v3.'/agg/'.$this->estabelecimento.'/'.$recorrencia->id.'/atualizar';
            $login =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'usuario_super_pay'])->first()->valor;
            $senha =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'senha_super_pay'])->first()->valor;
        }else{
            $host = $link_homologacao_v3.'/agg/'.$this->estabelecimento.'/'.$recorrencia->id.'/atualizar';
            $login = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'usuario_yapay_teste'])->first()->valor;
            $senha = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'senha_yapay_teste'])->first()->valor;
        }

        $auth = [ "username" => $login, "password" => $senha ];
        $http = new Client(['type' => 'json', 'auth' =>  $auth ]);

        try {
            $response = $http->put( $host, json_encode(['valor' => $valor]), ['type' => 'json', 'auth' =>  $auth ] );
        } catch (\Exception $e) {
            return false;
        }

        return (isset($response) && $response->isOk()) ? true : false;
    }

    /**
    *    Salvar etapas da Recorrencia 
    *        Condições:
    *            Sem response - criar uma nova
    *            Com response e recorrencia criada - salva result
    *            Com response e sem recorrencia - salva erro/falha
    *
    *    $response => resultado do response em JSON para a requisição em Client() 
    *                $id => código de identificação da recorrencia já criada anteriomente 

                    
    */
    private function setRecorrencia($result = null, $id = null){

        $carrinho = $this->request->session()->read('carrinho');
        $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
        $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
        $usuario = $carrinho->get('mdl_user');
        $numeroParcelas = $this->venda->get('numero_parcelas');
        $valor_parcelas = $carrinho->calcularParcela($numeroParcelas, true);

        if(is_null($result)){
            $recorrencia = $this->EcmRecorrencia->newEntity();
            $recorrencia->set('estabelecimento', $this->estabelecimento);
            $recorrencia->set('quantidade_cobrancas', $numeroParcelas);
            $recorrencia->set('numero_cobranca_restantes', $numeroParcelas);
            $recorrencia->set('ecm_venda', $this->venda);
            $recorrencia->set('ecm_tipo_pagamento', $tipoPagamento);
            $recorrencia->set('ecm_operadora_pagamento', $operadoraPagamento);
            $recorrencia->set('mdl_user', $usuario);
            $recorrencia->set('ip', $this->request->clientIp());
            $recorrencia->set('valor', $valor_parcelas);
            $recorrencia->set('status', 1);

            $recorrencia->set('data_primeira_cobranca', new \DateTime());
            $recorrencia->set('capturar', 'true');
        }else{ 

            $data_retorno = new \DateTime();
            // $transacao = $this->EcmTransacao->newEntity();

            if(isset($result['recorrencia'])){
                $returnRecorrencia = $result['recorrencia'];
                $id = $returnRecorrencia['numeroRecorrencia'];
            }

            try {
                $recorrencia = $this->EcmRecorrencia->find()->where(['id' => $id ])->first();
            } catch (RecordNotFoundException $e) {
                return false;
            }

            $recorrencia->set('data_retorno', $data_retorno);

            if(!empty($returnRecorrencia)){
                $recorrencia->set('numero_cobranca_restantes', $returnRecorrencia['numeroCobrancaRestantes']);
                $recorrencia->set('mensagem_venda', $returnRecorrencia['mensagemVenda']);
                /*
                $transacao->set('tid',$returnRecorrencia['numeroComprovanteVenda']);
                $transacao->set('arp', $returnRecorrencia['autorizacao']);
                $transacao->set('lr', $returnRecorrencia['codigoTransacaoOperadora']);
                $transacao->set('ecm_transacao_status_id', $returnRecorrencia['statusTransacao']);

                if($returnRecorrencia['statusTransacao'] == 1 || $returnRecorrencia['statusTransacao'] == 2) 
                    $transacao->set('data_cobranca', $data_retorno);
                */

            }
            // else{
            //     $transacao->set('ecm_transacao_status_id', 5);
            // }

            if(isset($result['erro'])&& !is_null($result['erro']['mensagem'])){
                $recorrencia->set('mensagem_venda', $result['erro']['mensagem']);
                // $transacao->set('erro', $result['erro']['mensagem']);
            }
            /*
            $transacao->set('estabelecimento', $this->estabelecimento);
            $transacao->set('ecm_recorrencia_id', $recorrencia->id);
            $transacao->set('data_envio', $data_retorno);
            $transacao->set('data_retorno', $data_retorno);
            $transacao->set('descricao', 'Recorrência SuperPay V3');
            $transacao->set('ecm_venda', $this->venda);
            $transacao->set('ecm_tipo_pagamento', $this->venda->get('ecm_tipo_pagamento'));
            $transacao->set('ecm_operadora_pagamento', $this->venda->get('ecm_operadora_pagamento') );
            $transacao->set('mdl_user', $carrinho->get('mdl_user'));
            $transacao->set('ip', $this->request->clientIp());
            $transacao->set('valor', $carrinho->calcularParcela($numeroParcelas, true));
            $transacao->set('id_integracao', $recorrencia->get('id').'001' );
            */

            if(!empty($returnRecorrencia)){
                $recorrencia->set('id_integracao', $returnRecorrencia['numeroRecorrencia']);
                $recorrencia->set('numero_cobranca_restantes', $returnRecorrencia['numeroCobrancaRestantes']);
                $recorrencia->set('mensagem_venda', $returnRecorrencia['mensagemVenda']);
                /*
                $transacao->set('tid',$returnRecorrencia['numeroComprovanteVenda']);
                $transacao->set('arp', $returnRecorrencia['autorizacao']);
                $transacao->set('lr', $returnRecorrencia['codigoTransacaoOperadora']);
                $transacao->set('ecm_transacao_status_id', $returnRecorrencia['statusTransacao']);
                */
            }

            // $this->EcmTransacao->save($transacao);

        }

        return $this->EcmRecorrencia->save($recorrencia);
    }


    /*

        estabelecimento : Código único que identifica o estabelecimento dentro do SuperPay
        recorrencia : {
                        numeroRecorrencia: Codigo
                        valor :     Valor da recorrência. 
                        formaPagamento : Código da forma de pagamento escolhida
                        modalidade : Código que identifica a modalidade. 1 = Crédito
                        quantidadeCobrancas : Quantidade máxima de cobranças. Caso seja enviado o valor 0, a recorrência será feita até que a mesma seja cancelada
                        dataPrimeiraCobranca: Data da primeira cobrança. Formato DD/MM/AAAA
                        periodicidade: 1 – Semanal / 2 – Quinzenal / 3 – Mensal / 4 – Bimestral, 5 – Trimestral / 6 – Semestral / 7 – Anual
                        urlNotificacao :    URL para notificação de cada cobrança da recorrência (campainha)    
                        processarImediatamente : true – Cobrança será iniciada imediatamente ao cadastro.
                                                false – Primeira cobrança será agendada para data posterior 
        }

        dadosCartao {
                        nomePortador    Nome do titular do cartão de crédito (Exatamente como escrito no cartão)   
                        numeroCartao    Numero do cartão de crédito, sem espaços ou traços  - Até 22 caracteres  
                        codigoSeguranca Código de segurança do cartão (campo não é armazenado pelo SuperPay)  - Até 4 caracteres
                        dataValidade    Data de validade do cartão. Formato mm/yyyy
        }

        dadosCobranca {
                        nomeComprador - Até 100 caracteres
                        emailComprador - Até 100 caracteres
                        tipoCliente -   1 - Pessoa Física / 2 - Pessoa Jurídica
                        documento - Até 22 caracteres
                        telefone : {
    
                        }
        }


        Tabela Tipo de Telefone (Tipo - Código)
                            Outros - 1
                            Residencial - 2
                            Comercial - 3
                            Recados - 4
                            Cobrança - 5
                            Temporário - 6

        TABELA STATUS
                Código  - Nome - Descrição
                1 - Pago e Capturado - Transação está autorizada e confirmada
                2 - Pago e Não Capturado - Transação está autoriza, aguardando confirmação (captura)
                3 - Não Pago - Transação negada pela operadora.
                5 - Transação em Andamento - Comum para pagamentos com transferências e cartão redirect
                8 - Aguardando Pagamento - Comum para pagamentos com boletos e pedidos em reprocessamento
                9 - Falha na Operadora - Houve um problema no processamento com a operadora
                13 - Cancelada - Transação cancelada na adquirente.
                14 - Estornada - A venda foi estornada na adquirente.
                15 - Em Análise de Fraude - A transação foi enviada para o sistema de análise de riscos / fraudes. Status transitório
                17 - Recusado pelo AntiFraude - A transação foi negada pelo sistema análise de Risco / Fraude.
                18 - Falha na Antifraude - Falha. Não foi possível enviar pedido para a análise de Risco / Fraude, porém será reenviada.
                21 - Boleto Pago a menor - O boleto está pago com valor divergente do emitido.
                22 - Boleto Pago a maior - O boleto está pago com valor divergente do emitido.
                23 - Estorno Parcial - A venda estonada na adquirente parcialmente.
                24 - Estorno Não Autorizado - O Estorno não foi autorizado pela operadora.
                25 - Falha no estorno    Falha ao enviar estorno para a operadora
                30 - Transação em Curso - Transação em curso de pagamento.
                31 - Transação já Paga - Transação já existente e finalizada na operadora.
                40 - Aguardando Cancelamento   - Processo de cancelamento em andamento.


    */
    private function getDados($recorrencia ){

        if(!is_null($recorrencia)){
            $carrinho = $this->request->session()->read('carrinho');
            $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
            $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
            $formaPagamento = $operadoraPagamento->get('ecm_forma_pagamento');
            $numeroParcelas = $this->venda->get('numero_parcelas');
            $valor_parcelas = $carrinho->calcularParcela($numeroParcelas, true);

            $dados['estabelecimento'] = $this->estabelecimento;
            $dados['recorrencia']['numeroRecorrencia'] = intval($recorrencia->id);
            $dados['recorrencia']['formaPagamento'] = intval($operadoraPagamento->dataname);
            $dados['recorrencia']['valor'] = number_format($valor_parcelas, 2, '', '');
            $dados['recorrencia']['modalidade'] = "1"; // 1 = Crédito
            $dados['recorrencia']['periodicidade'] = "3"; //3 – Mensal
            //// criar page ecommerce para atualizar dados na recorrencia 
            $dados['recorrencia']['urlNotificacao'] = \Cake\Routing\Router::url('/forma-pagamento-super-pay-recorrencia/campainha', true);
            $dados['recorrencia']['processarImediatamente'] = "true";
            $dados['recorrencia']['dataPrimeiraCobranca'] = date('d/m/Y');
            $dados['recorrencia']['quantidadeCobrancas'] = (string) $numeroParcelas;

            $dataCartao = $this->request->data('cartao');
            if(is_null($dataCartao)){
                $dataCartao = JWT::decode(
                    $this->request->session()->read('info')
                , Security::salt(), array('HS256'));
                $dataCartao = get_object_vars($dataCartao);
            }
            $dadosCartao['nomePortador'] = $dataCartao['nome'];
            $dadosCartao['numeroCartao'] = $dataCartao['numero'];
            $dadosCartao['codigoSeguranca'] = $dataCartao['codigo'];
            $dadosCartao['dataValidade'] = $dataCartao['mesSelect'].'/'.$dataCartao['anoSelect'];
            $dados['recorrencia']['dadosCartao'] = $dadosCartao;

            $usuario = $carrinho->get('mdl_user');
            $usuario = $this->MdlUser->find()
                                     ->select([
                                        'MdlUser.id', 'username', 'idnumber', 'firstname', 'lastname', 'email', 'phone1', 'phone2'
                                    ])
                                    ->contain(
                                        [
                                            'MdlUserDados' => [
                                                'fields' => [
                                                    'cpf' => 'numero', 'crea' => 'numero_crea',
                                                    'tipousuario' => 'tipousuario', 'funcionarioqisat' => 'funcionarioqisat'
                                                ]
                                            ]
                                        ]
                                    )
                                    ->where(['MdlUser.id' => $usuario->get('id') ])
                                    ->first();

            $documento = preg_replace("/[^0-9]/", "", $usuario->get('cpf'));
            if(!$documento) $documento = '12345678900';
            $nome = $usuario->get('firstname').' '.$carrinho->get('mdl_user')->get('lastname');
            $fone = preg_replace('/[^0-9]/', '', $usuario->get('phone1'));
            if(!$fone) $fone = preg_replace('/[^0-9]/', '', $usuario->get('phone2'));
            if(!$fone){
                // valor default para não imposibilitar a venda - campus obrigatórios
                $fone = '123456789'; 
                $ddd = '12';
            }else{
                $ddd = substr($fone, 0, 2);
                $fone = substr($fone, 2);
            }

            $dadosCobranca['nomeComprador'] = $nome;
            $dadosCobranca['emailComprador'] = $usuario->get('email');
            $dadosCobranca['documento'] = $documento;
            $dadosCobranca['telefone']['tipoTelefone'] = 1;
            $dadosCobranca['telefone']['ddi'] = '55'; // verificar pais???? 
            $dadosCobranca['telefone']['ddd'] = $ddd;
            $dadosCobranca['telefone']['telefone'] = $fone;
            $dadosCobranca['tipoCliente'] = 1;
            $dados['recorrencia']['dadosCobranca'] = $dadosCobranca;  
        }


        return $dados;
    }

    private function enviarEmailCompra($carrinho){
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('MdlUser');

        $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));
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

        $params = [$carrinho->get('mdl_user')->get('email'), $paramsEmail];
        $this->request->session()->delete('compraConfirmada');
        return $this->getMailer('FormaPagamentoSuperPayRecorrencia.FormaPagamentoSuperPayRecorrencia')->send('compraEfetuada', $params);
    }

    private function inserirRepasse($usuario, $carrinho, $mensagem){
        $this->loadModel('Repasse.EcmRepasse');

        $repasse = $this->EcmRepasse->newEntity();
        $repasse->set('status', EcmRepasse::STATUS_NAO_ATENDIDO);
        $repasse->set('assunto_email', 'QiSat | Confirmação de Pedido');
        $repasse->set('corpo_email',$mensagem);
        $repasse->set('ecm_alternative_host_id', $carrinho->get('ecm_alternative_host_id'));
        $repasse->set('data_registro', new \DateTime());

        if(!is_null($usuario))
            $repasse->set('mdl_user_cliente_id', $usuario->id);

        if($ecmRepasseOrigem = $this->EcmRepasse->EcmRepasseOrigem->find()->where(['LOWER(origem)' => 'Site QiSat'])->first())
            $repasse->set('ecm_repasse_origem_id', $ecmRepasseOrigem->id);

        if($ecmRepasseCategoria = $this->EcmRepasse->EcmRepasseCategorias->find()->where(['LOWER(categoria)' => 'Compra Efetuada'])->first())
            $repasse->set('ecm_repasse_categorias_id', $ecmRepasseCategoria->id);

        $this->EcmRepasse->save($repasse);
    }
}
