<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 15/06/2016
 * Time: 10:16
 */

namespace FormaPagamentoSuperPay\Controller;

use ADmad\JwtAuth\Auth\JwtAuthenticate;
use App\Auth\AESPasswordHasher;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
use Cake\Routing\Router;
use Carrinho\Model\Entity\EcmCarrinho;
use Carrinho\Model\Entity\EcmCarrinhoItem;
use Carrinho\Model\Entity\EcmTransacao;
use Carrinho\Model\Entity\EcmVendaStatus;
use FormaPagamento\Controller\FormaPagamentoAbstractController;
use FormaPagamentoSuperPay\Lib\SuperPayGateway\LocawebGateway;
use FormaPagamentoSuperPay\Lib\SuperPayGateway\LocawebGatewayConfig;
use Promocao\Controller\AppController;
use Repasse\Model\Entity\EcmRepasse;

class FormaPagamentoSuperPayController extends AppController implements FormaPagamentoAbstractController
{
    use MailerAwareTrait;

    private $venda = null;
    public function initialize()
    {
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

        if(is_null($carrinho)){
            $this->Flash->error(__('Usuário não selecionado!'));
            return $this->redirect(['plugin' => false, 'controller' => 'usuario', 'action' => 'listar-usuario']);
        }

        $this->venda = $this->EcmVenda->find()->contain(['EcmOperadoraPagamento' => ['EcmFormaPagamento'], 'EcmTipoPagamento'])
            ->where(['ecm_carrinho_id'=>$carrinho->get('id')])->first();

        if(is_null($this->venda)){
            return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
        }

        return parent::beforeFilter($event);
    }

    private function configuracao(){
        $this->loadModel('Configuracao.EcmConfig');

        $ambienteProducao = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'ambiente_producao'])->first();

        $environment = 'sandbox';
        $token = null;
        if($ambienteProducao->valor == 1){
            $environment = 'production';
            $token = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'token_super_pay'])->first();
        }else{
            $token = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_token_super_pay'])->first();
        }

        LocawebGatewayConfig::setEnvironment($environment);
        LocawebGatewayConfig::setToken($token->valor);
    }

    public function requisicao()
    {
        $transacao = $this->criarTransacao();

        $dados = $this->getDados($transacao);
        $requisicao = LocawebGateway::criar($dados)->sendRequest();
        $transacaoRetorno = $requisicao->transacao;

        $erro = '';
        if ($transacaoRetorno) {
            if (is_null($transacaoRetorno->erro)) {
                $this->criarTransacao($transacao, $transacaoRetorno);

                if($this->request->is('post')){
                    return ['sucesso' => true, 'mensagem' => __('Pagamento no cartão gerado com sucesso'),
                        'venda' => $this->venda->id,
                        'url' => $transacaoRetorno->url_acesso];
                }

                return $this->redirect($transacaoRetorno->url_acesso);

            }else{
                $erro = 'Erro '.$transacaoRetorno->erro->codigo.': '.$transacaoRetorno->erro->mensagem;
            }
        }

        $transacao->set('erro', $transacaoRetorno->erro->mensagem);
        $transacao->set('ecm_transacao_status', $this->getStatusTransacao('erro'));
        $this->EcmTransacao->save($transacao);

        $linkEmail = "<a href='mailto:central@qisat.com.br'>central@qisat.com.br </a>";
        $mensagem = 'Ocorreu um erro ao iniciar uma transação com o pedido {0}, informe o suporte do site através do e-mail
                     '.$linkEmail.' ou entre em contato com a nossa central de vendas (48) 3332-5000.';

        if($this->request->is('post')){
            return ['sucesso' => false, 'mensagem' => __($erro)];
        }

        $this->Flash->error(__($mensagem,[$dados['pedido']['numero']]),['params' => ['hiddenClick' => false]]);
        $this->Flash->error(__($erro));
        return $this->redirect(['plugin' => 'carrinho', 'controller' => '', 'action' => 'confirmardados']);
    }

    public function retorno($numero = null)
    {
        
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Entidade.EcmAlternativeHost');

        $referer  =$this->request->session()->read('url_requisicao');
        $referer = substr($referer, 0, strpos($referer, "/", 8)+1);
        $link_site = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'dominio_acesso_site'])->first()->valor;

        if(is_null($numero))
            $numeroTransacao = $this->request->data('numeroTransacao');
        else
            $numeroTransacao = $numero;

        $transacao = null;
        $retorno = ['sucesso' => false ];

        try {
            $transacao = $this->EcmTransacao->get($numeroTransacao);
        } catch (RecordNotFoundException $e) {
            if(!is_null($numero)){
                if (strpos($referer, $link_site) == false)
                    return $this->redirect(['plugin' => 'Carrinho','controller' => '','action' => 'confirmardados', 'error' => 'true' ]);
                else
                    $retorno['mensagem'] = __('Transação não encontrada');
            }
        }

        if(!is_null($transacao)) {

            $requisicao = LocawebGateway::consultar($transacao->get('id_integracao'))->sendRequest();
            $requisicao = $requisicao->transacao;
            $transacao->set('data_retorno', new \DateTime());

            if (is_null($requisicao->erro)) {
                $status = $requisicao->status;
                if(!is_null($requisicao->detalhes)){
                    $transacao->set('nsu', $requisicao->detalhes->nsu);
                    $transacao->set('pan', $requisicao->detalhes->pan);
                    $transacao->set('arp', $requisicao->detalhes->arp);
                    $transacao->set('lr', $requisicao->detalhes->lr);
                }

                if($status == 'paga'|| $status == 'paga_nao_capturada'){
                    $carrinho = $this->request->session()->read('carrinho');

                    if($carrinho->checkItensStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO)){
                        $carrinhoNovo = $carrinho->novaEntidadeComValores();
                        $this->EcmCarrinho->save($carrinhoNovo);

                        $carrinhoNovo->addItensPorStatus($carrinho->get('ecm_carrinho_item'), EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);
                        $carrinho->removeItensPorStatus(EcmCarrinhoItem::STATUS_AGUARDANDO_PAGAMENTO);

                        $this->EcmCarrinho->save($carrinhoNovo);

                        $this->request->session()->write('carrinho', $carrinhoNovo);
                    }

                    $vendaStatus = $this->EcmVenda->EcmVendaStatus
                        ->find()->where(['status' => EcmVendaStatus::STATUS_FINALIZADO])->first();

                    $this->venda->set('ecm_venda_status', $vendaStatus);
                    $carrinho->set('status', EcmCarrinho::STATUS_FINALIZADO);

                    $this->EcmVenda->save($this->venda);
                    $this->EcmCarrinho->save($carrinho);

                    $status = $this->getStatusTransacao($status);
                    $transacao->set('ecm_transacao_status', $status);

                    $this->EcmTransacao->save($transacao);

                    $retorno = ['sucesso' => true, 'mensagem' => __('Transação efetivada com sucesso')];
                    
                    if($mail = $this->enviarEmailCompra($carrinho)){
                        $this->loadModel('MdlUser');
                        $usuario = $this->MdlUser->get($carrinho->mdl_user_id);
                        $mensagem = substr($mail['message'], stripos($mail['message'],'<body>'), stripos( $mail['message'], '</body>')-stripos( $mail['message'],'<body>'));
                        $this->inserirRepasse($usuario, $carrinho, $mensagem);
                    }

                }else{
                    if($status == 'cancelada'){
                        $this->request->session()->write('error_transacao', '3');
                        $retorno['mensagem'] = __('Transação cancelada pelo Usuário');
                    }else if($status == 'negada'){
                         $this->request->session()->write('error_transacao', '6');
                        if(!is_null($requisicao->detalhes))
                            $retorno['mensagem'] = $transacao->getMsgErrorOperadora($requisicao->detalhes->lr);
                        else 
                            $retorno['mensagem'] = __('Pagamento Negado');
                    }

                    $status = $this->getStatusTransacao($status);
                    $transacao->set('ecm_transacao_status', $status);
                    $this->EcmTransacao->save($transacao);
                }
            }else{
                $this->request->session()->write('error_transacao', '4');
                $retorno[ 'mensagem'] = $transacao->getMsgErrorOperadora($requisicao->detalhes->lr);
                $transacao->set('erro', $transacao->getMsgErrorOperadora($requisicao->detalhes->lr));
                $transacao->set('ecm_transacao_status', $this->getStatusTransacao('erro'));
                $this->EcmTransacao->save($transacao);
            }
        }

        if (strpos($referer, $link_site) === false){
            if($retorno['sucesso']){
                $mensagem = $this->getMensagem($requisicao->status);
                $this->Flash->success(__($mensagem));

                $usuario = $carrinho->get('mdl_user');
                $venda = $this->venda;

                $this->set(compact('usuario','venda'));
                $this->set('_serialize', ['usuario','venda']);

                $this->render("confirmarcompra");
            }else{
                $this->Flash->error($retorno['mensagem']);
                return $this->redirect(['plugin' => 'Carrinho','controller' => '','action' => 'confirmardados', 'error' => 'true' ]);
            }
        }else
            return $retorno;
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

    private function enviarEmailCompra($carrinho){
        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('MdlUser');

        $usuario = $this->MdlUser->get($carrinho->mdl_user_id);
        $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_noreply'])->first()->valor;
        $fromEmailTitle = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_from_title'])->first()->valor;

        $aesHash = new AESPasswordHasher();
        $senha = $aesHash->decrypt($usuario->get('password'));

        $valorTotal = $this->venda->valor_parcelas * $this->venda->numero_parcelas;

        $paramsEmail = [
            'usuario' => $usuario,
            'senha' => $senha,
            'produtos' => $carrinho->get('ecm_carrinho_item'),
            'pedido' => $this->venda->id,
            'valor' => number_format($valorTotal, 2, ',', '')
        ];

        $params = [ [$fromEmail => $fromEmailTitle], $usuario->get('email'), $paramsEmail];

        $this->getMailer('FormaPagamentoSuperPay.FormaPagamentoSuperPay')->send('compraEfetuada', $params);

        $adminEmail = $fromEmail = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'email_sistema'])->first()->valor;
        $params = [ [$fromEmail => $fromEmailTitle], $adminEmail, $paramsEmail];
        $this->request->session()->delete('compraConfirmada');
        return $this->getMailer('FormaPagamentoSuperPay.FormaPagamentoSuperPay')->send('compraEfetuada', $params);
    }

    private function criarTransacao($transacao = null, $retornoRequisicao = null){
        if(is_null($transacao)) {
            $transacao = $this->EcmTransacao->newEntity();
        }

        $carrinho = $this->request->session()->read('carrinho');
        
        $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
        $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
        $usuario = $carrinho->get('mdl_user');
        $valorVenda = $this->venda->get('valor_parcelas');
        $numeroParcelas = $this->venda->get('numero_parcelas');
        $valorVenda = $valorVenda * $numeroParcelas;

        if(is_null($retornoRequisicao)) {
            $transacao->set('ecm_venda', $this->venda);
            $transacao->set('ecm_tipo_pagamento', $tipoPagamento);
            $transacao->set('ecm_operadora_pagamento', $operadoraPagamento);
            $transacao->set('mdl_user', $usuario);
            $transacao->set('ip', $this->request->clientIp());
            $transacao->set('valor', $valorVenda);
        }else{
            $status = $this->getStatusTransacao($retornoRequisicao->status);

            $transacao->set('id_integracao', $retornoRequisicao->id);
            $transacao->set('ecm_transacao_status', $status);
            $transacao->set('url', $retornoRequisicao->url_acesso);

            if(!is_null($retornoRequisicao->detalhes)){
                $transacao->set('tid', $retornoRequisicao->detalhes->tid);
            }
        }

        return $this->EcmTransacao->save($transacao);
    }

    private function getStatusTransacao($descricao){

        $status = $this->EcmTransacao->EcmTransacaoStatus->find()->where(['status' => $descricao])->first();
        if(is_null($status)){
            $status = $this->EcmTransacao->EcmTransacaoStatus->newEntity();
            $status->set('status', $descricao);

            $status = $this->EcmTransacao->EcmTransacaoStatus->save($status);
        }

        return $status;
    }

    private function getDados(EcmTransacao $transacao){

        $carrinho = $this->request->session()->read('carrinho');

        $operadoraPagamento = $this->venda->get('ecm_operadora_pagamento');
        $tipoPagamento = $this->venda->get('ecm_tipo_pagamento');
        $formaPagamento = $operadoraPagamento->get('ecm_forma_pagamento');

        $valorVenda = $this->venda->get('valor_parcelas');
        $numeroParcelas = $this->venda->get('numero_parcelas');

        $valorVenda = $valorVenda * $numeroParcelas;

        if($this->request->is('post')){
            $dados['url_retorno'] = Router::url(['plugin' => false,
                    'controller' => 'pagamento'], true);
            $jwt = new JwtAuthenticate($this->_components, []);
            $dados['url_retorno'] .=  '?token='.$jwt->getToken($this->request);
        }else {
            $dados['url_retorno'] = Router::url(['controller' => '', 'action' =>'retorno'], true);
        }

        $dados['capturar']    = true;

        //$dados['pedido']['numero'] = (string)$transacao->get('id');
        $dados['pedido']['numero'] = $transacao->get('id');
        $dados['pedido']['total'] = $valorVenda;
        $dados['pedido']['moeda'] = 'real';
        $dados['pedido']['descricao'] = 'Carrinho de Compras';

        $dados['pagamento']['meio_pagamento'] = $formaPagamento->get('dataname');
        $dados['pagamento']['bandeira'] = $operadoraPagamento->get('dataname');
        $dados['pagamento']['parcelas'] = $numeroParcelas;
        $dados['pagamento']['tipo_operacao'] = $tipoPagamento->get('dataname');


        $usuario = $carrinho->get('mdl_user');
        //$usuario = $this->MdlUser->get($usuario->get('id'), ['contain' => ['MdlUserEndereco']]);
        $usuario = $this->MdlUser->get($usuario->get('id'));

        $nome = $usuario->get('firstname').' '.$carrinho->get('mdl_user')->get('lastname');
        $dados['comprador']['nome'] = $nome;
        $dados['comprador']['endereco'] = $usuario->get('address');
        $dados['comprador']['cidade'] = $usuario ->get('city');

        if($this->MdlUser->MdlUserEndereco->exists(['id' => $usuario->get('id')])){
            $endereco = $this->MdlUser->MdlUserEndereco->get($usuario->get('id'));
            $dados['comprador']['complemento'] = $endereco->get('complement');
            $dados['comprador']['numero'] = $endereco->get('number');
            $dados['comprador']['cep'] = $endereco->get('cep');
            $dados['comprador']['bairro'] = $endereco->get('district');
            $dados['comprador']['estado'] = $endereco->get('state');
        }

        return $dados;
    }

    private function getMensagem($descricao){
        $mensagem = [
            'paga' => 'Compra efetuada com sucesso',
            'paga_nao_capturada' => 'Compra efetuada com sucesso',
            'negada' => 'Transação negada pela operadora de cartão de crédito',
            'cancelada' => 'A transação foi cancelada',
            'falha_na_operadora' => 'Transação negada pela operadora de cartão de crédito',
            'recusado_antifraude ' => 'Transação negada pelo sistema antifraude'
        ];

        return $mensagem[$descricao];
    }

}