<?php

namespace FormaPagamentoBoletoPhp\Controller;

use ADmad\JwtAuth\Auth\JwtAuthenticate;
use App\Auth\AESPasswordHasher;
use Cake\Event\Event;
use Cake\Routing\Router;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use FormaPagamento\Controller\FormaPagamentoAbstractController;
use Promocao\Controller\AppController;
use Cake\Mailer\MailerAwareTrait;
use Repasse\Model\Entity\EcmRepasse;

class FormaPagamentoBoletoPhpController extends AppController implements FormaPagamentoAbstractController
{
    use MailerAwareTrait;

    private $venda = null;
    private $taxa_boleto = 0;
    private $dias_de_prazo_para_pagamento = 2;

    public function initialize()
    {
        $this->loadModel('Carrinho.EcmVenda');
        $this->loadModel('Carrinho.EcmCarrinho');
        $this->loadModel('MdlUser');

        parent::initialize();
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
            if(!is_null($this->request->query('id'))){
                $this->Flash->error(__('Você não tem autorização para acessar esse local.'));
                $this->redirect(['plugin' => false, 'controller' => 'pages', 'action' => 'index']);
            }

            $carrinho = $this->request->session()->read('carrinho');

            if (is_null($carrinho)) {
                $this->Flash->error(__('Usuário não selecionado!'));
                return $this->redirect(['plugin' => false, 'controller' => 'usuario', 'action' => 'listar-usuario']);
            }

            $this->venda = $this->EcmVenda->find()->contain(['EcmOperadoraPagamento' => ['EcmFormaPagamento'], 'EcmTipoPagamento'])
                ->where(['ecm_carrinho_id' => $carrinho->get('id')])->first();

            if (is_null($this->venda)) {
                return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
            }
        }

        return parent::beforeFilter($event);
    }

    public function requisicao()
    {
        /**
         * Realizar pedido para s3eng e atualizar ecm_venda(pedido, pedido_status)
         */

        $this->loadModel('FormaPagamentoBoletoPhp.EcmVendaBoleto');
        $ecmVendaBoleto = $this->EcmVendaBoleto->find()->where(['ecm_venda_id' => $this->venda->id])->first();

        if(is_null($ecmVendaBoleto)) {
            $parcela = 1;
            do {
                $ecmVendaBoleto = $this->EcmVendaBoleto->newEntity();
                $ecmVendaBoleto->parcela = $parcela;
                $ecmVendaBoleto->ecm_venda_id = $this->venda->id;
                $ecmVendaBoleto->data = $this->venda->data->modify('+' . ($parcela - 1) . ' month');
                $this->EcmVendaBoleto->save($ecmVendaBoleto);
            } while (++$parcela < $this->venda->numero_parcelas);
        }

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
                if($mail = $this->enviarEmailCompra($carrinho)){
                    $this->loadModel('MdlUser');
                    $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));
                    $mensagem = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                    $this->inserirRepasse($carrinho, $usuario, $mensagem);
                }
            }

            $this->Flash->success(__('Compra efetuada com sucesso.'));
        } else {
            $this->Flash->error(__('Erro ao finalizar a compra. Por favor, tente outra vez.'));
        }

        if($this->request->is('post')){
            return ['sucesso' => true, 'mensagem' => __('Pagamento em boleto gerado com sucesso'),
                'venda' => $this->venda->id,
                'url' => Router::url(['plugin' => 'FormaPagamentoBoletoPhp',
                    'controller' => 'FormaPagamentoBoletoPhp', 'action' =>'boleto'], true)];
        }else{
            $usuario = $carrinho->get('mdl_user');
            $venda = $this->venda;

            $this->set(compact('usuario','venda'));
            $this->set('_serialize', ['usuario','venda']);

            $this->render("confirmarcompra");
        }
    }

    private function enviarEmailCompra($carrinho){
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('MdlUser');

        $link = Router::url(['plugin' => 'FormaPagamentoBoletoPhp',
            'controller' => 'FormaPagamentoBoletoPhp', 'action' => 'boleto', $this->venda->id], true);
        $usuario = $this->MdlUser->get($carrinho->get('mdl_user')->get('id'));

        $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $aesHash = new AESPasswordHasher();
        $senha = $aesHash->decrypt($usuario->get('password'));

        $paramsEmail = [
            'usuario' => $carrinho->get('mdl_user'),
            'senha' => $senha,
            'produtos' => $carrinho->get('ecm_carrinho_item'),
            'pedido' => $this->venda->id,
            'vencimento' => $this->calcularVencimento($this->venda->data)->format("d/m/Y"),
            'valor' => number_format($this->venda->valor_parcelas, 2, ',', ''),
            'linkBoleto' => $link
        ];

        $params = [ [$fromEmail => $fromEmailTitle], $carrinho->get('mdl_user')->get('email'), $paramsEmail];

        $this->getMailer('FormaPagamentoBoletoPhp.FormaPagamentoBoletoPhp')->send('compraEfetuada', $params);

        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $params = [ [$fromEmail => $fromEmailTitle], $adminEmail, $paramsEmail];
        $this->request->session()->delete('compraConfirmada');
        return $this->getMailer('FormaPagamentoBoletoPhp.FormaPagamentoBoletoPhp')->send('compraEfetuada', $params);
    }

    private function inserirRepasse($carrinho, $usuario, $mensagem){
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

    public function retorno(){}

    public function boleto(){
        $this->viewBuilder()->layout(false);

        $dadosboleto = $this->getDados();
        $this->modulo_11($dadosboleto["nosso_numero"]);

        $taxa_boleto = $this->taxa_boleto;

        $this->set(compact('dadosboleto','taxa_boleto'));
        $this->set('_serialize', ['dadosboleto','taxa_boleto']);
    }

    private function getDados(){
        $pedido = str_pad($this->venda->id, 6 , "0", STR_PAD_LEFT);
        if(isset($this->venda->pedido)){
            $pedido = str_pad($this->venda->pedido, 6 , "0", STR_PAD_LEFT);
        }

        $parcelaAtual = 1;
        $usuario = $this->MdlUser->get($this->venda->mdl_user_id);
        $chaveAltoQi =  str_pad($usuario->idnumber, 6 , "0", STR_PAD_LEFT);

        $numeroParcela = $parcelaAtual < 10? '0'.$parcelaAtual: $parcelaAtual;
        $numeroParcela .= $this->venda->numero_parcelas < 10? '0'.$this->venda->numero_parcelas: $this->venda->numero_parcelas;

        $numeroDocumento = $pedido.$numeroParcela;
        $nossoNumero = $chaveAltoQi.'0'.$numeroDocumento;

        $taxa_boleto = $this->taxa_boleto;

        $diaVencimento = $this->venda->data;
        if($parcelaAtual == 1){
            $diaVencimento = $this->calcularVencimento($diaVencimento);
        }
        $valorParcela = $this->venda->valor_parcelas + $taxa_boleto;

        $data_venc = $diaVencimento->format("d/m/Y");  // Prazo de X dias OU informe data: "13/04/2006";
        $valor_cobrado = number_format($valorParcela, 2, ',', ''); // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
        $valor_cobrado = str_replace(",", ".",$valor_cobrado);
        $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

        $dadosboleto["campo_fixo_obrigatorio"] = "1";       // campo fixo obrigatorio - valor = 1
        $dadosboleto["inicio_nosso_numero"] = "9";          // Inicio do Nosso numero - obrigatoriamente deve começar com 9;
        $dadosboleto["nosso_numero"] = $nossoNumero;  // Nosso numero sem o DV - REGRA: Máximo de 16 caracteres! (Pode ser um número sequencial do sistema, o cpf ou o cnpj)
        $dadosboleto["numero_documento"] = $numeroDocumento;	// Num do pedido ou do documento
        $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto["data_documento"] = date("d/m/Y",time()); // Data de emissão do Boleto
        $dadosboleto["data_processamento"] = date("d/m/Y",time()); // Data de processamento do boleto (opcional)
        $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

        // DADOS DO SEU CLIENTE
        $dadosboleto["sacado"] = $usuario->idnumber.' '.$usuario->firstname.' '.$usuario->lastname;
        $dadosboleto["endereco1"] = $usuario->address.' '.$usuario->number.' '.$usuario->complement;
        $dadosboleto["endereco2"] = $usuario->city.' '.$usuario->state.' '.$usuario->cep;

        // INSTRUÇÕES PARA O CAIXA
        $parcela = $parcelaAtual . '/' . $this->venda->numero_parcelas;

        $dadosboleto["instrucoes1"] = '- Referente a Parcela '.$parcela;
        $dadosboleto["instrucoes2"] = '- Após o vencimento cobrar multa de 2%';
        $dadosboleto["instrucoes3"] = '- Após o vencimento cobrar juros de R$ 0,30 por dia de atraso';

        // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto["quantidade"] = "";
        $dadosboleto["valor_unitario"] = "";
        $dadosboleto["aceite"] = "N";
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "OU";

        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //

        // DADOS DA SUA CONTA - CEF
        $dadosboleto["agencia"] = "0408"; // Num da agencia, sem digito
        $dadosboleto["conta"] = "13877"; 	// Num da conta, sem digito
        $dadosboleto["conta_dv"] = "4"; 	// Digito do Num da conta

        // DADOS PERSONALIZADOS - CEF
        $dadosboleto["conta_cedente"] = "043598"; // ContaCedente do Cliente, sem digito (Somente Números)
        $dadosboleto["conta_cedente_dv"] = "2"; // Digito da ContaCedente do Cliente
        $dadosboleto["carteira"] = "SR5";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)

        $this->loadModel('Configuracao.EcmConfig');

        $enderecovalor = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'enderecovalor'])->first();

        // SEUS DADOS
        $dadosboleto["identificacao"] = "QiSat | Boleto de Pagamento";
        $dadosboleto["cpf_cnpj"] = "";
        //$dadosboleto["endereco"] = "Coloque o endereço da sua empresa aqui";
        $dadosboleto["endereco"] = $enderecovalor->valor;
        $dadosboleto["cidade_uf"] = 'Florianópolis / Santa Catarina';
        $dadosboleto["cedente"] = 'MN TECNOLOGIA E TREINAMENTO LTDA';

        return $dadosboleto;
    }

    private function calcularVencimento($dataVenda){
        $diaVencimento = $dataVenda;
        return $diaVencimento->modify('+'.$this->dias_de_prazo_para_pagamento.' days');
    }

    private function modulo_11($num, $base=9, $r=0)  {
        /**
         *   Autor:
         *           Pablo Costa <pablo@users.sourceforge.net>
         *
         *   Fun��o:
         *    Calculo do Modulo 11 para geracao do digito verificador
         *    de boletos bancarios conforme documentos obtidos
         *    da Febraban - www.febraban.org.br
         *
         *   Entrada:
         *     $num: string num�rica para a qual se deseja calcularo digito verificador;
         *     $base: valor maximo de multiplicacao [2-$base]
         *     $r: quando especificado um devolve somente o resto
         *
         *   Sa�da:
         *     Retorna o Digito verificador.
         *
         *   Observa��es:
         *     - Script desenvolvido sem nenhum reaproveitamento de c�digo pr� existente.
         *     - Assume-se que a verifica��o do formato das vari�veis de entrada � feita antes da execu��o deste script.
         */

        $soma = 0;
        $fator = 2;

        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                // restaura fator de multiplicacao para 2
                $fator = 1;
            }
            $fator++;
        }

        /* Calculo do modulo 11 */
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;
            if ($digito == 10) {
                $digito = 0;
            }
            return $digito;
        } elseif ($r == 1){
            $resto = $soma % 11;
            return $resto;
        }
    }
}