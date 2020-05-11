<?php
/**
 * Created by PhpStorm.
 * User: inty.castillo
 * Date: 24/05/2018
 * Time: 10:16
 */

namespace Cupom\Controller;

use App\Controller\WscController;
use Cake\Event\Event;
use Cupom\Model\Entity\EcmCupom;

class WscCupomController extends WscController
{

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->set('_serialize', true);
    }

    /**
     * @param null $chave
     * @param null $email
     */
    public function gerarCupom($id = null, $email = null){
        $retorno = ['sucesso' => false, 'mensagem' => __('Cupom não encontrado.')];
        if(is_null($id))
            $retorno['mensagem'] = __('Favor, informe um codigo de cupom valido.');
        if(is_null($email))
            $retorno['mensagem'] = __('Favor, informe um email valido.');

        $dataAtual = new \DateTime();

        $this->loadModel('Cupom.EcmCupom');
        $ecmCupom = $this->EcmCupom->find('all')
            ->where([
                'EcmCupom.id' => $id,
                'EcmCupom.datainicio <=' => $dataAtual->format('Y-m-d'),
                'EcmCupom.datafim >=' => $dataAtual->format('Y-m-d'),
                'EcmCupom.habilitado' => 'true',
                'EcmCupom.tipo_aquisicao' => EcmCupom::CAMPANHA_COM_EMAIL
            ])->first();

        if(!is_null($ecmCupom) && !is_null($email)){
            $ecmCupomCampanha = $this->EcmCupom->EcmCupomCampanha->newEntity();
            $ecmCupomCampanha->ecm_cupom_id = $ecmCupom->id;
            $ecmCupomCampanha->email = $email;
            $ecmCupomCampanha->datacriacao = time();
            $this->EcmCupom->EcmCupomCampanha->save($ecmCupomCampanha);
            $retorno = ['sucesso' => true, 'token' => $ecmCupom->chave];
        }

        $this->set(compact('retorno'));
        $this->set('_serialize', ['retorno']);
    }

    /**
     * @param null $chave
     */
    public function validarCupom($chave = null){
        $retorno = ['sucesso' => false, 'mensagem' => __('Carrinho vazio.')];

        $ecmCarrinho = $this->request->session()->read('carrinho');
        if(!is_null($ecmCarrinho) && !is_null($ecmCarrinho->ecm_carrinho_item)) {
            if(is_null($ecmCarrinho->mdl_user_modified_id)){
                $this->loadModel('Cupom.EcmCupom');

                $cupons = [];
                if(!is_null($chave))
                    $cupons = $this->EcmCupom->buscarCupons(null, null, null, $chave);

                $cupom = null;
                $user = $this->Auth->user();
                if(!is_null($user)){
                    $this->loadModel('MdlUser');
                    $user = $this->MdlUser->newEntity($user);
                    $cupons = array_merge($cupons, $this->EcmCupom->buscarCupons($user, null, null, $chave));

                    if(empty($cupons))
                        $cupons = array_merge($cupons, $this->EcmCupom->buscarCupons($user));

                    $cupom = $this->EcmCupom->buscarMelhorCupom($cupons, $ecmCarrinho, $user->id);
                }

                if (is_null($cupom))
                    $cupom = $this->EcmCupom->buscarMelhorCupom($cupons, $ecmCarrinho);

                $cupomAtual = $this->request->session()->read('cupom');
                if(!is_null($cupom) && !is_null($cupomAtual) && $cupom->id == $cupomAtual->id) {
                    $retorno['mensagem'] = __('O cupom já encontra-se inserido.');
                } else {
                    $this->loadModel('Carrinho.EcmCarrinho');
                    $this->loadModel('Promocao.EcmPromocao');

                    foreach ($ecmCarrinho->ecm_carrinho_item as $item) {
                        if (!is_null($item->ecm_cupom)) {
                            unset($item->ecm_cupom);
                            $item->ecm_cupom_id = null;
                            $item->set('valor_produto_desconto', $item->valor_produto);
                        }
                        if(isset($item->ecm_promocao)){
                            $desconto = $ecmCarrinho->verificarDesconto($item->ecm_produto, [$item->ecm_promocao], $cupom);
                        } else {
                            $listaPromocao = $this->EcmPromocao->buscaPromocoesAtivasUsuario($item->ecm_produto, $ecmCarrinho->get('mdl_user_id'));
                            $desconto = $ecmCarrinho->verificarDesconto($item->ecm_produto, $listaPromocao, $cupom);
                        }
                        if (!isset($desconto) || !array_key_exists("promocao", $desconto)) {
                            unset($item->ecm_promocao);
                            $item->ecm_promocao_id = null;
                            $item->set('valor_produto_desconto', $item->valor_produto);
                        }
                        if(isset($desconto)){
                            if(array_key_exists("promocao", $desconto)){
                                $item->set('ecm_promocao', $desconto['promocao']);
                                $item->set('ecm_promocao_id', $desconto['promocao']->id);
                            }
                            if(array_key_exists("cupom", $desconto)){
                                $item->set('ecm_cupom', $desconto['cupom']);
                                $item->set('ecm_cupom_id', $desconto['cupom']->id);
                            }
                            $item->set('valor_produto_desconto', $desconto['valorTotal']);
                        }
                        $ecmCarrinho->addItem($item);
                    }

                    if (!is_null($cupom)) {
                        $retorno = ['sucesso' => true, 'mensagem' => __('Cupom inserido com sucesso.')];

                        $this->request->session()->write('cupom', $cupom);
                    } else {
                        $retorno = ['sucesso' => true, 'mensagem' => __('Cupom não encontrado.')];

                        if($this->request->session()->check('cupom')){
                            $this->request->session()->delete('cupom');

                            if(is_null($chave))
                                $retorno['mensagem'] = __('Cupom removido com sucesso.');
                        }
                    }

                    $this->EcmCarrinho->save($ecmCarrinho);
                    $this->request->session()->write('carrinho', $ecmCarrinho);
                }
            } else {
                $retorno['mensagem'] = __('O cupom não pode ser aplicado a essa proposta.');
            }
        }
        $this->set(compact('retorno'));
    }
}