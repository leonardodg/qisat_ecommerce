<?php
namespace Carrinho\Controller;

use Carrinho\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\Http\Exception\NotFoundException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * EcmTransacao Controller
 *
 * @property \Carrinho\Model\Table\EcmTransacaoTable $EcmTransacao */
class EcmTransacaoController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $condition = [];
        $datanow = new \DateTime();
        $datanow->setDate($datanow->format('Y'), $datanow->format('n'), 1);

        $tabelas = [ 
            'transacao'     => [ 'dia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0], 'total' => 0 ],

            'cartao'        => ['dia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                'paga' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                'negada' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                'aguardando_pagamento' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                'cancelada' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                'erro' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                'estorno' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                'total' => [ 'dia' => 0, 'paga' => 0, 'negada' => 0, 'aguardando_pagamento' => 0, 'cancelada' => 0, 'erro' => 0, 'estorno' => 0]],

            'recorrencia'   => [ 'dia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'paga' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'negada' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'aguardando_pagamento' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'cancelada' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'erro' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'estorno' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'total' => [ 'dia' => 0, 'paga' => 0, 'negada' => 0, 'aguardando_pagamento' => 0, 'cancelada' => 0, 'erro' => 0, 'estorno' => 0]]

            ];

            $list_mes = $this->EcmTransacao->find('list', [
                'keyField' => function($e){
                    return $e->get('data_envio')->format('m/Y');
                },
                'valueField' => function($e){
                    return $e->get('data_envio')->format('m/Y');
                },
                'groupField' => function ($e) {
                    return $e->get('data_envio')->format('Y');
                }
            ])->order(['year(data_envio)' => 'asc', 'month(data_envio)' => 'asc'])
                ->where(['year(data_envio)' => '2018'])
                ->toArray();

            if(!empty($this->request->data)){
                $id = $this->request->data('id');
                $status = $this->request->data('status_transacao');
                $mes = $this->request->data('mes');
                $pedido = $this->request->data('pedido');
                $venda = $this->request->data('venda');
                $proposta = $this->request->data('proposta');

                if(!empty($id) || !empty($pedido) || !empty($proposta) || !empty($venda)){

                    if(strlen(trim($id)) > 0){
                        if(strrpos($id, ',') >= 0){
                            $condition['OR']['EcmTransacao.id IN'] = explode(',', $id);
                            $condition['OR']['EcmTransacao.id_integracao IN'] = explode(',', $id);
                        }
                        else{
                            $condition['OR']['EcmTransacao.id'] = $id;
                            $condition['OR']['EcmTransacao.id_integracao'] = $id;
                        }
                    }

                    if(strlen(trim($pedido)) > 0)
                        $condition['OR']['EcmVenda.pedido'] = $pedido;
                    if(strlen(trim($proposta)) > 0)
                        $condition['OR']['EcmVenda.proposta'] = $proposta;
                    if(strlen(trim($venda)) > 0)
                        $condition['OR']['EcmVenda.id'] = $venda;
                }else{
                    if(strlen(trim($status)) > 0){

                        switch ($status) {
                            case 'aguardando_pagamento':
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 2, 'EcmTransacao.ecm_transacao_status_id' => 2];
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id in' => [3,4], 'EcmTransacao.ecm_transacao_status_id in' => [5,8,15]];
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id in' => [3,4], 'EcmTransacao.ecm_transacao_status_id IS NULL'];
                                break;
                            case 'cancelada':
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 2, 'EcmTransacao.ecm_transacao_status_id' => 3];
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id in' => [3,4], 'EcmTransacao.ecm_transacao_status_id in' => [13,40]];
                                break;
                            case 'erro':
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 2, 'EcmTransacao.ecm_transacao_status_id' => 4];
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id in' => [3,4], 'EcmTransacao.ecm_transacao_status_id in' => [18,25,31]];
                                break;
                            case 'estorno':
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 2, 'EcmTransacao.ecm_transacao_status_id' => 5];
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id in' => [3,4], 'EcmTransacao.ecm_transacao_status_id in' => [14,23,24,27]];
                                break;
                            case 'negada':
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 2, 'EcmTransacao.ecm_transacao_status_id' => 6];
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id in' => [3,4], 'EcmTransacao.ecm_transacao_status_id in' => [3,9,17,18]];
                                break;
                            case 'paga':
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 2, 'EcmTransacao.ecm_transacao_status_id' => 7];
                                    $condition['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id in' => [3,4], 'EcmTransacao.ecm_transacao_status_id' => 1];
                                break;
                        }
                    }
                
                    if (strlen(trim($mes)) > 0)
                        $datanow = \DateTime::createFromFormat('m/Y', $mes);

                    $year = $datanow->format('Y');
                    $month = $datanow->format('n');
                    $ndias = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $dataInicio = clone $datanow;
                    $dataFim = clone $datanow;
                    $dataInicio->setDate($year, $month, 1);
                    $dataInicio->setTime(0, 0, 0);
                    $dataFim->setDate($year, $month, $ndias);
                    $dataFim->setTime(23, 59, 59);
                    $condition['EcmTransacao.data_envio >='] = $dataInicio->format('Y-m-d H:i:s');
                    $condition['EcmTransacao.data_envio <='] = $dataFim->format('Y-m-d H:i:s');
                }

            }else{
                $year = $datanow->format('Y');
                $month = $datanow->format('n');
                $ndias = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $dataInicio = clone $datanow;
                $dataFim = clone $datanow;
                $dataInicio->setDate($year, $month, 1);
                $dataInicio->setTime(0, 0, 0);
                $dataFim->setDate($year, $month, $ndias);
                $dataFim->setTime(23, 59, 59);
                $condition['EcmTransacao.data_envio >='] = $dataInicio->format('Y-m-d H:i:s');
                $condition['EcmTransacao.data_envio <='] = $dataFim->format('Y-m-d H:i:s');
            }

            $ecmTransacao = $this->EcmTransacao->find('all')
                                        ->contain([
                                            'MdlUser', 'EcmTipoPagamento' => ['EcmFormaPagamento'],
                                            'EcmOperadoraPagamento', 'EcmVenda', 'EcmRecorrencia'
                                        ])
                                    ->where($condition)
                                    ->order(['EcmTransacao.id' => 'DESC']);

            foreach ($ecmTransacao as $trans) {
                if($trans->data_envio){
                    $dia = $trans->data_envio->format('j');

                    $tabelas['transacao']['dia'][$dia-1]++;
                    $tabelas['transacao']['total']++;
                    $forma = is_null($trans->ecm_recorrencia_id)? 'cartao': 'recorrencia';
                    $tabelas[$forma]['dia'][$dia-1]++;
                    $tabelas[$forma]['total']['dia']++;

                    if($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller == 'SuperPayV3' || $trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller == 'SuperPayRecorrencia'){

                        if((is_null($trans->erro)|| empty($trans->erro)) && ($trans->lr != '00' && !is_null($trans->lr ))){
                            $trans->erro = $trans->getMsgErrorOperadora($trans->lr);
                        }

                        $trans->status = $trans->getStatusV3($trans->ecm_transacao_status_id);

                    }else if($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller == 'SuperPay' || ($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller == 'FastConnect')){
                        $trans->status = $trans->getStatusV1($trans->ecm_transacao_status_id);
                    }

                    if($trans->status){
                        $tabelas[$forma][$trans->status][$dia-1]++;
                        $tabelas[$forma]['total'][$trans->status]++;
                    }
                }
            }

        $this->set(compact('ecmTransacao', 'list_mes', 'datanow','tabelas'));
        $this->set('_serialize', ['ecmTransacao', 'list_mes', 'datanow', 'tabelas']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Transacao id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmTransacao = $this->EcmTransacao->get($id, [
            'contain' => ['EcmTransacaoStatus', 'MdlUser', 'EcmTipoPagamento', 'EcmOperadoraPagamento', 'EcmVenda']
        ]);

        $this->set('ecmTransacao', $ecmTransacao);
        $this->set('_serialize', ['ecmTransacao']);
    }
}
