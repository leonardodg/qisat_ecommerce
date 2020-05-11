<?php
/**
 * Created by PhpStorm.
 * User: inty.castillo
 * Date: 11/07/2018
 * Time: 09:16
 */

namespace FormaPagamentoBoletoRegistrado\Controller;

use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\Routing\Router;
use Cake\Utility\Xml;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use FormaPagamento\Controller\FormaPagamentoAbstractController;
use Promocao\Controller\AppController;

class FormaPagamentoBoletoRegistradoController extends AppController implements FormaPagamentoAbstractController
{
    const LINK_PRODUCAO = 'https://cobranca.homologa.bb.com.br:7101/registrarBoleto';
    const LINK_HOMOLOGACAO = 'https://cobranca.homologa.bb.com.br:7101/registrarBoleto';
    const URL_OAUTH = 'https://oauth.hm.bb.com.br/oauth/token';

    private $venda = null;
    private $dias_de_prazo_para_pagamento = 2;
    public function initialize()
    {
        $this->loadModel('Carrinho.EcmCarrinho');
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('FormaPagamentoBoletoPhp.EcmVendaBoleto');
        $this->loadModel('Vendas.EcmVenda');
        $this->loadModel('MdlUser');

        parent::initialize();
        $this->configuracao();
    }

    private function configuracao(){
        $this->loadModel('Configuracao.EcmConfig');

        $token = (object)$this->EcmConfig->find('list', [
                                                'keyField' => function($q){
                                                    return str_replace('bb_boleto_registrado_', '', $q->nome);
                                                },
                                                'valueField' => 'valor'])
                                          ->where(['nome LIKE "bb_boleto_registrado_%"'])
                                          ->toArray();
        
        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();
        if($ambienteProducao->valor == 1){
            $this->environment = 'prodution';
            $this->endPoint = self::LINK_PRODUCAO;
        }else{
            $this->environment = 'sandbox';
            $this->endPoint = self::LINK_HOMOLOGACAO;
        }

        $this->token_type = $token->token_type;
        $this->access_token = $token->access_token;
        $this->auth = $token->token_type . ' ' . $token->access_token;

    }


    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        //$this->response->type('application/x-www-form-urlencoded');
        //$this->RequestHandler->renderAs($this, 'json');
        //$this->response->type('application/json');
        $this->set('_serialize', true);
    }

    public function beforeFilter(Event $event)
    {
        if($this->request->params['action'] == "boleto" && !is_null($this->request->query('id'))){
            if(isset($this->Auth->user()['id'])){
                $userid = $this->Auth->user()['id'];
            } else if(!is_null($this->CookieOverride->read('QiSat'))){
                $cookie = $this->CookieOverride->read('QiSat');
                $userid = $this->MdlUser->find('all', ['fields' => ['id'], 'conditions' => [
                    'username' => $cookie['username'], 'password' => $cookie['password']
                ]])->first()->id;
            }
            if(intval($this->request->query('id')) && isset($userid)){
                $this->venda = $this->EcmVenda->find('all', ['contain' => ['EcmTipoPagamento', 'EcmOperadoraPagamento' => ['EcmFormaPagamento']],
                    'conditions' => ['EcmVenda.id' => $this->request->query('id'), 'mdl_user_id' => $userid]])->first();
            }
        }

        if(is_null($this->venda)) {
            $carrinho = $this->request->session()->read('carrinho');

            if (is_null($carrinho)) {
                $this->Flash->error(__('Usuário não selecionado!'));
                return $this->redirect(['plugin' => false, 'controller' => 'usuario', 'action' => 'listar-usuario']);
            }

            $this->venda = $this->EcmVenda->find()
                ->contain(['EcmOperadoraPagamento' => ['EcmFormaPagamento' => ['EcmAlternativeHost']], 'EcmTipoPagamento'])
                ->where(['ecm_carrinho_id' => $carrinho->get('id')])->first();

            if (is_null($this->venda)) {
                return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
            }
        }

        return parent::beforeFilter($event);
    }

    private function gravar($texto)
    {
        $arquivo = ROOT . DS . "log_boleto_" . date('Ymd') . ".txt";
        $fp = fopen($arquivo, "a+");
        $texto = date('H:i:s') . " - " . $texto . "\n\n\n";
        fwrite($fp, $texto);
        fclose($fp);
    }

    public function requisicao()
    {
        $retorno = null;
        $carrinho = $this->request->session()->read('carrinho');
        $return = ['sucesso' => false, 'mensagem' => _("Falha na Compra!")];
        $http = ($this->environment == 'prodution' ) ? new Client() : new Client(['ssl_verify_peer' => false,'ssl_verify_peer_name' => false ]);

        $usuario = $this->MdlUser->find()
                                 ->contain(['MdlUserDados' => function($q){
                                        return $q->select(['numero' => 'numero', 'tipousuario' => 'tipousuario']);
                                    }])
                                 ->contain(['MdlUserEndereco' => function($q){
                                        return $q->select(['number', 'complement' => 'complement', 'district' => 'district', 'state' => 'state', 'cep' => 'cep']);
                                    }])
                                 ->where(['mdl_user_id' => $carrinho->get('mdl_user')->get('id')])
                                 ->first();

        $ecmVendaBoleto = $this->EcmVendaBoleto->newEntity();
        //$ecmVendaBoleto->parcela = 1;
        $ecmVendaBoleto->ecm_venda_id = $this->venda->id;
        $ecmVendaBoleto->data = new \DateTime();
        $ecmVendaBoleto->data_vencimento = date('Y-m-d', strtotime('+3 days'));
        $this->EcmVendaBoleto->save($ecmVendaBoleto);

        $phone = "";
        if(!empty($usuario->phone1))
            $phone = $usuario->phone1;
        else if(!empty($usuario->phone2))
            $phone = $usuario->phone2;

        if(!empty($phone))
            $phone = preg_replace("/[^0-9]/", "", $phone);

        if(empty($carrinho->mdl_user->idnumber))
            $carrinho->mdl_user->idnumber = '1234567';

        //<<<XML
        $xml_string = '
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:sch="http://www.tibco.com/schemas/bws_registro_cbr/Recursos/XSD/Schema.xsd">
                <soapenv:Header/>
                <soapenv:Body>
                    <sch:requisicao>
                    <sch:numeroConvenio>3106527</sch:numeroConvenio>
                    <sch:numeroCarteira>17</sch:numeroCarteira>
                    <sch:numeroVariacaoCarteira>19</sch:numeroVariacaoCarteira>

                    <sch:codigoModalidadeTitulo>1</sch:codigoModalidadeTitulo>
                    <sch:dataEmissaoTitulo>'.date('d.m.Y').'</sch:dataEmissaoTitulo>
                    <sch:dataVencimentoTitulo>'.date("d.m.Y", time() + ($this->dias_de_prazo_para_pagamento * 86400)).'</sch:dataVencimentoTitulo>
                    <sch:valorOriginalTitulo>'.number_format($carrinho->calcularTotal(), 2).'</sch:valorOriginalTitulo>
                    <sch:codigoTipoDesconto>0</sch:codigoTipoDesconto>
                <!--sch:dataDescontoTitulo>11.10.2018</sch:dataDescontoTitulo-->
                <!--sch:percentualDescontoTitulo/-->
                <!--sch:valorDescontoTitulo>10</sch:valorDescontoTitulo-->
                <!--sch:valorAbatimentoTitulo/-->
                <!--sch:quantidadeDiaProtesto>0</sch:quantidadeDiaProtesto-->
                    <sch:codigoTipoJuroMora>0</sch:codigoTipoJuroMora>
                <!--sch:percentualJuroMoraTitulo></sch:percentualJuroMoraTitulo-->
                <!--sch:valorJuroMoraTitulo></sch:valorJuroMoraTitulo-->
                    <sch:codigoTipoMulta>0</sch:codigoTipoMulta>
                <!--sch:dataMultaTitulo>14.10.2018</sch:dataMultaTitulo-->
                <!--sch:percentualMultaTitulo>10</sch:percentualMultaTitulo-->
                <!--sch:valorMultaTitulo></sch:valorMultaTitulo-->
                    <sch:codigoAceiteTitulo>N</sch:codigoAceiteTitulo>
                    <sch:codigoTipoTitulo>2</sch:codigoTipoTitulo>
                <!--sch:textoDescricaoTipoTitulo>DUPLICATA</sch:textoDescricaoTipoTitulo-->
                    <sch:indicadorPermissaoRecebimentoParcial>N</sch:indicadorPermissaoRecebimentoParcial>
                <!--sch:textoNumeroTituloBeneficiario>987654321987654</sch:textoNumeroTituloBeneficiario-->
                <!--sch:textoCampoUtilizacaoBeneficiario/-->
                <!--sch:codigoTipoContaCaucao>1</sch:codigoTipoContaCaucao-->
                <sch:textoNumeroTituloCliente>'."0003106527".str_pad($ecmVendaBoleto->id, 10, '0', STR_PAD_LEFT).'</sch:textoNumeroTituloCliente>
                <!--sch:textoMensagemBloquetoOcorrencia>Pagamento disponível até a data de vencimento</sch:textoMensagemBloquetoOcorrencia-->

                    <sch:codigoTipoInscricaoPagador>'.($usuario->get('tipousuario') == 'fisico' ? 1 : 2).'</sch:codigoTipoInscricaoPagador>
                    <sch:numeroInscricaoPagador>'.preg_replace("/[^0-9]/", "", $usuario->get('numero')).'</sch:numeroInscricaoPagador>
                    <sch:nomePagador>'.$usuario->get('firstname') . ' ' . $usuario->get('lastname').'</sch:nomePagador>
                    <sch:textoEnderecoPagador>'.$usuario->get('address').', '.$usuario->get('number').' '.$usuario->get('complement').'</sch:textoEnderecoPagador>
                    <sch:numeroCepPagador>'.preg_replace("/[^0-9]/", "", $usuario->get("cep")).'</sch:numeroCepPagador>
                    <sch:nomeMunicipioPagador>'.$usuario->get('city').'</sch:nomeMunicipioPagador>
                    <sch:nomeBairroPagador>'.$usuario->get("district").'</sch:nomeBairroPagador>
                    <sch:siglaUfPagador>'.$usuario->get("state").'</sch:siglaUfPagador>
                    <sch:textoNumeroTelefonePagador>'.$phone.'</sch:textoNumeroTelefonePagador>
                <!--sch:codigoTipoInscricaoAvalista/-->
                <!--sch:numeroInscricaoAvalista/-->
                <!--sch:nomeAvalistaTitulo/-->

                    <sch:codigoChaveUsuario>j'.str_pad($carrinho->mdl_user->idnumber, 7, '0', STR_PAD_LEFT).'</sch:codigoChaveUsuario>
                    <sch:codigoTipoCanalSolicitacao>5</sch:codigoTipoCanalSolicitacao>
                    </sch:requisicao>
                </soapenv:Body>
            </soapenv:Envelope>
        ';//XML

        try {
            $response = $http->post($this->endPoint,
                                        $xml_string, ['headers' => [
                                                                        "Authorization" => $this->auth,
                                                                        "SOAPACTION"    => "registrarBoleto",
                                                                        "Content-Type"  => "text/xml; charset=utf-8"
                                                                    ]]);
         } catch (\Exception $e) {
            if($this->request->is('post'))
                return ['sucesso' => false, 'mensagem' => "Http Error " . $e->getCode() . ": " . $e->getMessage()];

            $this->Flash->error(__("Http Error " . $e->getCode() . ": " . $e->getMessage()));
            return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
        }

        if($response->isOk()){
            $retorno = $response->body();
        }else if($response->code == 500){
            $retorno_faled = $response->body();

            if (strpos($retorno_faled, 'Expired Token') !== false) {
                try {
                    $token = $http->post(self::URL_OAUTH, [
                                                                'grant_type' => "client_credentials",
                                                                'scope'      => "cobranca.registro-boletos"
                                                            ], ['auth' => ['username' => 'eyJpZCI6IjgwNDNiNTMtZjQ5Mi00YyIsImNvZGlnb1B1YmxpY2Fkb3IiOjEwOSwiY29kaWdvU29mdHdhcmUiOjEsInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxfQ',
                                                                'password' => 'eyJpZCI6IjBjZDFlMGQtN2UyNC00MGQyLWI0YSIsImNvZGlnb1B1YmxpY2Fkb3IiOjEwOSwiY29kaWdvU29mdHdhcmUiOjEsInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxLCJzZXF1ZW5jaWFsQ3JlZGVuY2lhbCI6MX0']
                                                            ]);
                } catch (\Exception $e) {
                    if($this->request->is('post'))
                        return ['sucesso' => false, 'mensagem' => "Http Error " . $e->getCode() . ": " . $e->getMessage()];
        
                    $this->Flash->error(__("Http Error " . $e->getCode() . ": " . $e->getMessage()));
                    return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
                }

                if($token->isOk()){
                    $token = json_decode($token->body());

                    $ecmConfig = $this->EcmConfig->find()->where(['nome' => 'bb_boleto_registrado_token_type'])->first();
                    $ecmConfig->valor = $token->token_type;
                    $this->EcmConfig->save($ecmConfig);

                    $ecmConfig = $this->EcmConfig->find()->where(['nome' => 'bb_boleto_registrado_access_token'])->first();
                    $ecmConfig->valor = $token->access_token;
                    $this->EcmConfig->save($ecmConfig);

                    $this->token_type = $token->token_type;
                    $this->access_token = $token->access_token;
                    $this->auth = $token->token_type . ' ' . $token->access_token;

                }

                try {

                    $response = $http->post($this->endPoint,
                                                $xml_string, ['headers' => [
                                                                                "Authorization" => $this->auth,
                                                                                "SOAPACTION"    => "registrarBoleto",
                                                                                "Content-Type"  => "text/xml; charset=utf-8"
                                                                            ]]);
                 } catch (\Exception $e) {
                    if($this->request->is('post'))
                        return ['sucesso' => false, 'mensagem' => "Http Error " . $e->getCode() . ": " . $e->getMessage()];
        
                    $this->Flash->error(__("Http Error " . $e->getCode() . ": " . $e->getMessage()));
                    return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
                }

                if($response->isOk())
                    $retorno = $response->body();
                    
            }
        }

        $xml = simplexml_load_string(strtr($retorno, array(' xmlns:'=>' ','SOAP-ENV:'=>'','ns0:'=>'')));

        if(empty(trim((string)$xml->Body->resposta->linhaDigitavel))){
            $return = ['sucesso' => false, 'mensagem' => (string)$xml->Body->resposta->textoMensagemErro];
            if($this->request->is('post'))
                return $return;

            $this->Flash->error(__($return['mensagem']));
            return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
        }

        $ecmVendaBoleto->NumeroTituloCobrancaBb         = (string)$xml->Body->resposta->textoNumeroTituloCobrancaBb;
        $ecmVendaBoleto->PrefixoDependenciaBeneficiario = (int)$xml->Body->resposta->codigoPrefixoDependenciaBeneficiario;
        $ecmVendaBoleto->ContaCorrenteBeneficiario      = (int)$xml->Body->resposta->numeroContaCorrenteBeneficiario;
        $ecmVendaBoleto->codigoCliente                  = (int)$xml->Body->resposta->codigoCliente;
        $ecmVendaBoleto->linhaDigitavel                 = (string)$xml->Body->resposta->linhaDigitavel;
        $ecmVendaBoleto->codigoBarraNumerico            = (string)$xml->Body->resposta->codigoBarraNumerico;
        $ecmVendaBoleto->numeroContratoCobranca         = (int)$xml->Body->resposta->numeroContratoCobranca;

        if($this->EcmVendaBoleto->save($ecmVendaBoleto)){
            $carrinho = $this->request->session()->read('carrinho');

            if($carrinho->checkItensStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO)){
                $carrinhoNovo = $carrinho->novaEntidadeComValores();
                $this->EcmCarrinho->save($carrinhoNovo);

                $carrinhoNovo->addItensPorStatus($carrinho->get('ecm_carrinho_item'), EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);
                $carrinho->removeItensPorStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);

                $this->EcmCarrinho->save($carrinhoNovo);

                $this->request->session()->write('carrinho', $carrinhoNovo);
            }

            $carrinho->status = EcmCarrinho::STATUS_FINALIZADO;
            if($this->EcmCarrinho->save($carrinho)){
                if(!empty($this->request->session()->read('compraConfirmada'))
                    && $this->request->session()->read('compraConfirmada') == true) {
                    /*if($mail = $this->enviarEmailCompra($carrinho)){
                        $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));
                        $mensagem = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                        $this->inserirRepasse($carrinho, $usuario, $mensagem);
                    }*/
                }

                if($this->request->is('post')){
                    return ['sucesso' => true, 'mensagem' => __('Pagamento em boleto gerado com sucesso'),
                        'venda' => $this->venda->id,
                        'url' => Router::url(['plugin' => 'FormaPagamentoBoletoRegistrado',
                            'controller' => 'FormaPagamentoBoletoRegistrado', 'action' =>'boleto'], true)];
                }

                $usuario = $carrinho->get('mdl_user');
                $venda = $this->venda;

                $this->set(compact('usuario','venda'));
                $this->set('_serialize', ['usuario','venda']);

                $this->render("confirmarcompra");
                unset($return);
            }
        }

        if(isset($return)){
            if($this->request->is('post'))
                return $return;

            $this->Flash->error(__($return['mensagem']));
            return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
        }
    }

    /**
     * Retorna os dados do boleto registrado gerado
     */
    public function retorno()
    {
        //$this->gravar(print_r($this, true));
    }

    /**
     * Busca os dados de um boleto registrado
     */
    public function informa()
    {
        //$this->gravar("Informa: " . print_r($this, true));

    }

    /**
     * Busca os dados de todos os boletos registrados
     */
    public function sonda()
    {
        $this->gravar("Sonda: " . print_r($this, true));
    }

    public function boleto(){
        $this->viewBuilder()->layout(false);

        $dadosboleto = $this->getDados();

        $this->set(compact('dadosboleto'));
        $this->set('_serialize', ['dadosboleto']);
    }

    public function setVenda($venda = null){
        $this->venda = $venda;
    }

    public function getDados(){
        $usuario = $this->MdlUser->get($this->venda->mdl_user_id, [
            'contain' => [
                'MdlUserEndereco' => ['fields' => [
                    'number' => 'number', 'complement' => 'complement',
                    'address' => 'district', 'state' => 'state', 'cep' => 'cep'
                ]]
            ]
        ]);

        $nossoNumero = $this->EcmVendaBoleto->find()->select(['id'])
            ->where(['ecm_venda_id' => $this->venda->id])->last();
        $nossoNumero = str_pad($nossoNumero->id, 10, '0', STR_PAD_LEFT);

// DADOS DO BOLETO PARA O SEU CLIENTE
        $taxa_boleto = 0;
        $data_venc = date("d/m/Y", time() + ($this->dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
        $valor_cobrado = number_format($this->venda->valor_parcelas, 2, ',', ''); // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
        $valor_cobrado = str_replace(",", ".",$valor_cobrado);
        $valor_boleto = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

        $dadosboleto["nosso_numero"] = $nossoNumero;
        $dadosboleto["numero_documento"] = str_pad($this->venda->id, 10, '0', STR_PAD_LEFT);//$numeroDocumento;	// Num do pedido ou do documento
        $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto["data_documento"] = date("d/m/Y",time()); // Data de emissão do Boleto
        $dadosboleto["data_processamento"] = date("d/m/Y",time()); // Data de processamento do boleto (opcional)
        $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da vírgula

// DADOS DO SEU CLIENTE
        $dadosboleto["sacado"] = $usuario->idnumber.' '.$usuario->firstname.' '.$usuario->lastname;
        $dadosboleto["endereco1"] = $usuario->address.' '.$usuario->number.' '.$usuario->complement;
        $dadosboleto["endereco2"] = $usuario->city.' - '.$usuario->state.' - CEP: '.$usuario->cep;

// INFORMACOES PARA O CLIENTE
        $dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja QiSat";
        $dadosboleto["demonstrativo2"] = "Mensalidade referente a QiSat<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
        $dadosboleto["demonstrativo3"] = "QiSat - https://www.qisat.com.br";

// INSTRU��ES PARA O CAIXA
        $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
        $dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
        $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: qisat@qisat.com.br";
        $dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema E-commerce QiSat - www.qisat.com.br";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto["quantidade"] = "";
        $dadosboleto["valor_unitario"] = "";
        $dadosboleto["aceite"] = "N";
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "OU";

// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //

// DADOS DA SUA CONTA - BANCO DO BRASIL
        $dadosboleto["agencia"] = "3174"; // Num da agencia, sem digito
        $dadosboleto["conta"] = "114080"; 	// Num da conta, sem digito

// DADOS PERSONALIZADOS - BANCO DO BRASIL
        $dadosboleto["convenio"] = "3106527";  // Num do convênio - REGRA: 6 ou 7 ou 8 dígitos
    $dadosboleto["contrato"] = "114080"; // Num do seu contrato
        $dadosboleto["carteira"] = "17";
        $dadosboleto["variacao_carteira"] = "-019";  // Variação da Carteira, com traço (opcional)

// TIPO DO BOLETO
        $dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Convênio c/ 8 dígitos, 7 p/ Convênio c/ 7 dígitos, ou 6 se Convênio c/ 6 dígitos
    //$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Convênio c/ 6 dígitos: informe 1 se for Nossonúmero de até 5 dígitos ou 2 para opção de até 17 dígitos

        /*
        #################################################
        DESENVOLVIDO PARA CARTEIRA 18

        - Carteira 18 com Convenio de 8 digitos
          Nosso número: pode ser até 9 dígitos

        - Carteira 18 com Convenio de 7 digitos
          Nosso número: pode ser até 10 dígitos

        - Carteira 18 com Convenio de 6 digitos
          Nosso número:
          de 1 a 99999 para opção de até 5 dígitos
          de 1 a 99999999999999999 para opção de até 17 dígitos

        #################################################
        */

        $enderecovalor = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'enderecovalor'])->first();
// SEUS DADOS
        $dadosboleto["identificacao"] = "QiSat | Boleto de Pagamento";
        $dadosboleto["cpf_cnpj"] = "";
        $dadosboleto["endereco"] = $enderecovalor->valor;
        $dadosboleto["cidade_uf"] = 'Florianópolis / Santa Catarina';
        $dadosboleto["cedente"] = 'MN TECNOLOGIA E TREINAMENTO LTDA';

        return $dadosboleto;
    }
}
