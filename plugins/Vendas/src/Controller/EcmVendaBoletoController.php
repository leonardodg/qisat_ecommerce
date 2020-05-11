<?php
namespace Vendas\Controller;

use Vendas\Model\Entity\EcmVendaBoleto;

/**
 * EcmVendaBoleto Controller
 *
 * @property \Vendas\Model\Table\EcmVendaBoletoTable $EcmVendaBoleto */
class EcmVendaBoletoController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $id = $this->request->data['id'];
            $status = $this->request->data['status'];
            $ecmVendaBoleto = $this->EcmVendaBoleto->get($id, ['contain' => 'EcmVenda']);

            $retorno = ['sucesso' => false, 'mensagem' => __('Esse registro não pode ser alterado')];
            if($ecmVendaBoleto->get('status') == EcmVendaBoleto::STATUS_EM_ABERTO) {
                $ecmVendaBoleto->set('status', $status);

                $retorno = ['sucesso' => false, 'mensagem' => __('O status da venda não pode ser salvo. Por favor, tente novamente.')];

                if ($this->EcmVendaBoleto->save($ecmVendaBoleto)) {

                    if ($ecmVendaBoleto->get('status') != EcmVendaBoleto::STATUS_EM_ABERTO &&
                        $cursos = $this->listaCursosTrilha($ecmVendaBoleto)
                    ) {
                        $this->alterarPerfilCurso($ecmVendaBoleto, $cursos);
                    }
                    $ecmVendaBoletos = $this->EcmVendaBoleto->find('list', ['keyField' => 'id', 'valueField' => 'status',
                        'conditions' => ['ecm_venda_id' => $ecmVendaBoleto['ecm_venda_id']]
                    ])->toArray();
                    $status = 'Finalizada';
                    if (in_array("nPago", $ecmVendaBoletos)) {
                        $status = 'Boleto Não Pago';
                    } else if (in_array("Em aberto", $ecmVendaBoletos)) {
                        $status = 'Andamento';
                    }
                    if ($this->EcmVendaBoleto->EcmVenda->alterarStatusVenda($ecmVendaBoleto['ecm_venda_id'], $status)) {
                        $retorno = ['sucesso' => true];
                    }
                }
            }

            $this->render('ajax');
            die(json_encode($retorno));
        }else {
            $this->loadModel('Carrinho.EcmCarrinho');

            $conditions = [];
            $conditionsVenda = [];
            $conditionsUser = [];

            if (count($this->request->query)) {
                if (isset($this->request->query['idnumber']) && intval($this->request->query['idnumber'])) {
                    array_push($conditionsUser, 'MdlUser.idnumber = ' . $this->request->query['idnumber']);
                }
                if (isset($this->request->query['pedido']) && intval($this->request->query['pedido'])) {
                    array_push($conditionsVenda, 'EcmVenda.pedido = ' . $this->request->query['pedido']);
                }
                if (isset($this->request->query['parcela']) && intval($this->request->query['parcela'])) {
                    array_push($conditions, 'parcela = ' . $this->request->query['parcela']);
                }
                if (isset($this->request->query['status']) && $this->request->query['status'] != 'Todos') {
                    array_push($conditions, 'status = "' . $this->request->query['status'] . '"');
                }
                if(isset($this->request->query['ecm_tipo_produto']) && is_array($this->request->query['ecm_tipo_produto'])){
                    $tiposProduto = $this->request->query['ecm_tipo_produto'];

                    $carrinho = $this->EcmCarrinho
                        ->find('list',[
                            'fields'=>'id'
                        ])
                        ->matching('EcmCarrinhoItem', function ($q) use($tiposProduto){
                            return $q->matching('EcmProduto', function($q) use($tiposProduto){
                                return $q->matching('EcmTipoProduto', function($q) use($tiposProduto){
                                    return $q->where([
                                        'EcmTipoProduto.id IN' => $tiposProduto
                                    ]);
                                });
                            });
                        })
                        ->toArray();

                    $conditions['EcmCarrinho.id IN'] = $carrinho;
                }
            }

            $this->paginate = [
                'fields' => [
                    'is_fase' => '(
                        SELECT COUNT(v.id) AS is_fase
                        FROM ecm_venda v
                        INNER JOIN ecm_carrinho c ON c.id = v.ecm_carrinho_id
                        INNER JOIN ecm_carrinho_item ci ON ci.ecm_carrinho_id = c.id
                        INNER JOIN ecm_produto p ON p.id = ci.ecm_produto_id
                        INNER JOIN ecm_produto_ecm_tipo_produto ptp ON ptp.ecm_produto_id = p.id
                        WHERE ptp.ecm_tipo_produto_id = 47 AND v.id = EcmVenda.id
                    )',
                    'EcmVendaBoleto.id', 'EcmVendaBoleto.status', 'EcmVendaBoleto.parcela',
                    'EcmVenda.pedido', 'MdlUser.idnumber', 'MdlUser.firstname', 'MdlUser.lastname'
                ],
                'contain' => [
                    'EcmVenda' => [
                        'conditions' => $conditionsVenda,
                        'EcmCarrinho',
                        'MdlUser' => ['conditions' => $conditionsUser]
                    ]
                ],
                'conditions' => $conditions
            ];
            $ecmVendaBoleto = $this->paginate($this->EcmVendaBoleto);

            $status = array('Todos' => 'Todos', 'Em aberto' => 'Em aberto', 'Pago' => 'Pago', 'nPago' => 'Não Pago');

            $this->loadModel('FormaPagamento.EcmFormaPagamento');
            $formaPagamento = $this->EcmFormaPagamento->find()->select(['controller'])
                ->where(['tipo' => 'boleto', 'habilitado' => 'true'])->first();

            $optionsTipoProduto = $this->EcmFormaPagamento->EcmTipoProduto
                ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
                ->where(['habilitado'=>'true']);

            if ($this->request->is('get'))
                $this->request->data = $this->request->query;

            $this->set(compact('ecmVendaBoleto', 'status', 'formaPagamento', 'optionsTipoProduto'));
            $this->set('_serialize', ['ecmVendaBoleto', 'status', 'formaPagamento']);
        }
    }

    private function listaCursosTrilha($ecmVendaBoleto){
        $this->loadModel('WebService.MdlCourse');

        $cursos = $this->MdlCourse
            ->find('all', [
                'fields' => 'MdlCourse.id'
            ])
            ->matching(
                'EcmProduto', function($q) use($ecmVendaBoleto) {
                    return $q->matching('EcmTipoProduto', function($q)  {
                            return $q->where(['EcmTipoProduto.id' => 47]);
                        })
                        ->matching('EcmCarrinhoItem', function ($q) use($ecmVendaBoleto) {
                            return $q->matching('EcmCarrinho', function ($q) use($ecmVendaBoleto) {
                                return $q->matching('EcmVenda', function($q) use($ecmVendaBoleto) {
                                    return $q->matching('EcmVendaBoleto', function($q) use($ecmVendaBoleto) {
                                        return $q->where(['EcmVendaBoleto.id' => $ecmVendaBoleto->get('id')]);
                                    });
                                });
                            });
                        }
                    );
                }
            )->toList();

        return $cursos;
    }
    private function alterarPerfilCurso($ecmVendaBoleto, $listaCursos){
        $this->loadModel('MdlUser');

        $roleId = null;

        if($ecmVendaBoleto->get('status') == EcmVendaBoleto::STATUS_PAGO)
            $roleId = 11;

        foreach ($listaCursos as $curso) {
            $mdlUserEnrolments = $this->MdlUser->MdlUserEnrolments->find()
                ->matching('MdlEnrol', function ($q) use ($curso, $ecmVendaBoleto) {
                    return $q->where(['courseid' => $curso->get('id')]);
                })
                ->where([
                    'userid' => $ecmVendaBoleto->get('ecm_venda')->get('mdl_user_id'),
                    'MdlUserEnrolments.status' => 1
                ])
                ->first();

            $mdlRoleAssignments = $this->MdlUser->MdlRoleAssignments->find()
                ->matching('MdlContext', function ($q) use ($curso) {
                    return $q->where(['instanceid' => $curso->get('id')]);
                })
                ->where([
                    'userid' => $ecmVendaBoleto->get('ecm_venda')->get('mdl_user_id'),
                    'roleid' => 24
                ])
                ->first();

            if($mdlUserEnrolments && $mdlRoleAssignments){
                if(!is_null($roleId)) {
                    $mdlRoleAssignments->set('roleid', $roleId);
                    $this->MdlUser->MdlRoleAssignments->save($mdlRoleAssignments);
                } else {
                    $this->MdlUser->MdlUserEnrolments->delete($mdlUserEnrolments);
                    $this->MdlUser->MdlRoleAssignments->delete($mdlRoleAssignments);
                }
            }
        }
    }
}
