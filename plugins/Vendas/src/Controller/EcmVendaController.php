<?php
namespace Vendas\Controller;

use Vendas\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Http\Client;
use Cake\I18n\Number;

use App\Auth\AESPasswordHasher;
use Cake\Utility\Security;


/**
 * EcmVenda Controller
 *
 * @property \Vendas\Model\Table\EcmVendaTable $EcmVenda */
class EcmVendaController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {

        $this->loadModel('Vendas.DbaVendas');
        $this->loadModel('Vendas.DbaVendasProdutos');
        $this->loadModel('Vendas.DbaVendasServicos');
        $this->loadModel('Produto.EcmProduto');
        $this->loadModel('Produto.EcmProdutoEcmAplicacao');

        $tabelas = [ 
                    'venda'     => [ 'data' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'transacao' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'recorrencia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'boleto' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'top' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'total' => [0,0,0,0,0]],

                    'andamento' => [ 'data' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'transacao' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'recorrencia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'boleto' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'total' => [0,0,0,0]],

                    'finalizada' => [ 'data' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'transacao' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'recorrencia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'boleto' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'top' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'total' => [0,0,0,0,0]],

                    'estornada' => [ 'data' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'transacao' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'recorrencia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'boleto' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'total' => [0,0,0,0]],

                    'cancelada' => [ 'data' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'transacao' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'recorrencia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'boleto' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                    'total' => [0,0,0,0]]

                    ];

        $conditions = [];
        $conditionsUser = [];
        $conditionsDba = [];
        $conditionsCarrinho = [];
        $datanow = new \DateTime();
        $dbaqi =  array_key_exists('dbaqi', $this->request->data) ? $this->request->data('dbaqi') : true;
        $list = true; 

        $list_mes = $this->EcmVenda->find('list', [
            'keyField' => function($e){
                return $e->get('data')->format('m/Y');
            },
            'valueField' => function($e){
                return $e->get('data')->format('m/Y');
            },
            'groupField' => function ($e) {
                return $e->get('data')->format('Y');
            }
        ])->order(['year(data)' => 'DESC', 'month(data)' => 'DESC'])
            ->where(['year(data) >=' => '2017'])
            ->toArray();

        if($this->request->is('ajax')) {
            $this->autoRender = false;
            $id = $this->request->data['id'];
            $status = $this->request->data['status'];
            if ($this->EcmVenda->alterarStatusVenda($id, $status)) {
                echo json_encode(true);
            } else {
                $this->Flash->error(__('O status da venda não pode ser salvo. Por favor, tente novamente.'));
                echo json_encode(false);
            }
        }else if($this->request->is('post')) {
  
            if(!empty($this->request->data('codigo')) && intval($this->request->data['codigo'])){
                array_push($conditions, 'EcmVenda.id=' . $this->request->data['codigo']);
                $list = false;
                $dbaqi = false;
            }
            
            if(!empty($this->request->data['pedido']) && intval($this->request->data['pedido'])) {
                array_push($conditions, 'EcmVenda.pedido=' . $this->request->data['pedido']);
                $list = false;
                $dbaqi = false;
            }
            
            if(!empty($this->request->data['proposta']) && intval($this->request->data['proposta'])) {
                array_push($conditions, 'EcmVenda.proposta=' . $this->request->data['proposta']);
                $conditionsDba['DbaVendas.pedido'] = $this->request->data['proposta'];
                $list = false;
            }

            if (!empty($this->request->data['idnumber']) && !empty($this->request->data['idnumber'])) {
                array_push($conditionsUser, ['OR' => ['MdlUser.idnumber like "%' . $this->request->data['idnumber'] . '%"',
                    'OR' => ['MdlUser.id' => $this->request->data['idnumber']]]]);
                $list = false;
            }
            
            if($list) {
                if (!empty($this->request->data('mes')))
                    $datanow = \DateTime::createFromFormat('m/Y', $this->request->data('mes'));

                $year = $datanow->format('Y');
                $month = $datanow->format('n');
                $ndias = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $dataInicio = clone $datanow;
                $dataFim = clone $datanow;
                $dataInicio->setDate($year, $month, 1);
                $dataInicio->setTime(0, 0, 0);
                $dataFim->setDate($year, $month, $ndias);
                $dataFim->setTime(23, 59, 59);
                $conditions['EcmVenda.data >='] = $dataInicio->format('Y-m-d H:i:s');
                $conditions['EcmVenda.data <='] = $dataFim->format('Y-m-d H:i:s');
                $conditionsDba['DbaVendas.data_venda >='] = $dataInicio->format('Y-m-d H:i:s');
                $conditionsDba['DbaVendas.data_venda <='] = $dataFim->format('Y-m-d H:i:s');

                if (!empty($this->request->data['tipo']) && $this->request->data['tipo'] != "0") {
                    array_push($conditions, 'EcmVenda.ecm_tipo_pagamento_id=' . $this->request->data['tipo']);
                }
                if (!empty($this->request->data['status']) && $this->request->data['status'] != "0") {
                    array_push($conditions, 'EcmVenda.ecm_venda_status_id=' . $this->request->data['status']);
                }
                    
                if (!empty($this->request->data['nome']) && $this->request->data['nome'] != "0") {
                    array_push($conditions, 'EcmVenda.ecm_operadora_pagamento_id=' . $this->request->data['nome']);
                }
                if (!empty($this->request->data['fullname']) && $this->request->data['fullname'] != "0") {
                    array_push($conditionsCarrinho, 'EcmCarrinho.ecm_alternative_host_id=' . $this->request->data['fullname']);
                }
            }
        }else{
            array_push($conditions, 'EcmVenda.ecm_venda_status_id=2'); // DEFAULT FINALIZADA
            $datanow->setTime(0, 0, 0);
            $datanow->setDate($datanow->format('Y'), $datanow->format('n'), 1);
            $year = $datanow->format('Y');
            $month = $datanow->format('n');
            $ndias = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $dataInicio = clone $datanow;
            $dataFim = clone $datanow;
            $dataInicio->setDate($year, $month, 1);
            $dataInicio->setTime(0, 0, 0);
            $dataFim->setDate($year, $month, $ndias);
            $dataFim->setTime(23, 59, 59);
            $conditions['EcmVenda.data >='] = $dataInicio->format('Y-m-d H:i:s');
            $conditions['EcmVenda.data <='] = $dataFim->format('Y-m-d H:i:s');

            $conditionsDba['DbaVendas.data_venda >='] = $dataInicio->format('Y-m-d H:i:s');
            $conditionsDba['DbaVendas.data_venda <='] = $dataFim->format('Y-m-d H:i:s');
        }

        $ecmVendaStatus = $this->EcmVenda->EcmVendaStatus->find('list', ['keyField' => 'id', 'valueField' => 'status'])->toArray();
        $ecmVendaStatus[0] = "Todos";
        ksort($ecmVendaStatus);

        $ecmTipoPagamento = $this->EcmVenda->EcmTipoPagamento->find('list', ['keyField' => 'id', 'valueField' => 'nome'])->toArray();
        $ecmTipoPagamento[0] = "Todos";
        ksort($ecmTipoPagamento);

        $ecmOperadoraPagamento = $this->EcmVenda->EcmOperadoraPagamento->find('list', ['keyField' => 'id', 'valueField' => 'nome'])->toArray();
        $ecmOperadoraPagamento[0] = "Todos";
        ksort($ecmOperadoraPagamento);

        $ecmAlternativeHost = $this->EcmVenda->EcmCarrinho->EcmAlternativeHost->find('list', ['keyField' => 'id', 'valueField' => 'fullname'])->toArray();
        $ecmAlternativeHost[0] = "Todas Entidades";
        ksort($ecmAlternativeHost);

        $ecmVendas = $this->EcmVenda->find('all')
            ->contain([ 'EcmTipoPagamento' => ['EcmFormaPagamento'],
                            'EcmOperadoraPagamento', 'EcmVendaStatus',
                        'MdlUser' => ['conditions' => $conditionsUser],
                        'EcmCarrinho' => [
                            'EcmAlternativeHost', 
                            'EcmCarrinhoItem' => [ 'EcmProduto' => ['EcmTipoProduto'], 
                            'EcmCarrinhoItemEcmProdutoAplicacao' => ['EcmProdutoEcmAplicacao' => ['EcmProduto', 'EcmProdutoAplicacao']], 'EcmCarrinhoItemMdlCourse' ],
                        'conditions' => $conditionsCarrinho]
                        ])
                        ->where($conditions)
                        ->order(['EcmVenda.data' => 'desc'])->toArray();                            


        if($dbaqi){
           $dbaVendas = $this->DbaVendas->find('all')
                        ->contain([
                                    'MdlUser'=> ['conditions' => $conditionsUser],
                                    'DbaVendasProdutos' => ['EcmProduto', 'sort' => ['DbaVendasProdutos.sigla' => 'DESC']], 
                                    'DbaVendasServicos' => ['EcmProduto']
                                    ])
                        ->where($conditionsDba)
                        ->order(['DbaVendas.data_venda' => 'DESC'])->toArray();

            foreach ($dbaVendas as $venda) {
                // Exibir Resultados Na Tabelas
                $dia = $venda->data_venda->format('j');
                $tabelas['venda']['data'][$dia-1]++;
                $tabelas['venda']['total'][0]++;
                $tabelas['venda']['top'][$dia-1]++;
                $tabelas['venda']['total'][4]++;
                $tabelas['finalizada']['data'][$dia-1]++;
                $tabelas['finalizada']['total'][0]++;
                $tabelas['finalizada']['top'][$dia-1]++;
                $tabelas['finalizada']['total'][4]++;


                // var_dump($venda);die;
                $this->DbaVendas->searchProductsApps($venda);
            }
            // END FOREACH VENDAS
        }
        // IF END DBAQI
                        
        foreach ($ecmVendas as $venda) {
            $dia = $venda->data->format('j');
            $tabelas['venda']['data'][$dia-1]++;
            $tabelas['venda']['total'][0]++;

            $forma = $venda->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->tipo;

            if($forma == 'cartao'){
                $forma = 'transacao';
                $tabelas['venda']['transacao'][$dia-1]++;
                $tabelas['venda']['total'][1]++;
                $kt = 1;
            }else if($forma == 'cartao_recorrencia'){
                $forma = 'recorrencia';
                $tabelas['venda']['recorrencia'][$dia-1]++;
                $tabelas['venda']['total'][2]++;
                $kt = 2;
            }else if($forma == 'boleto'){
                $forma  = 'boleto';
                $tabelas['venda']['boleto'][$dia-1]++;
                $tabelas['venda']['total'][3]++;
                $kt = 3;
            }else
                $forma = '';
            
            switch ($venda->ecm_venda_status->status) {
                case 'Andamento':
                        $tabelas['andamento']['data'][$dia-1]++;
                        $tabelas['andamento']['total'][0]++;
                        if($forma){
                            $tabelas['andamento'][$forma][$dia-1]++;
                            $tabelas['andamento']['total'][$kt]++;
                        }
                    break;
                case 'Finalizada':
                        $tabelas['finalizada']['data'][$dia-1]++;
                        $tabelas['finalizada']['total'][0]++;
                        if($forma){
                            $tabelas['finalizada'][$forma][$dia-1]++;
                            $tabelas['finalizada']['total'][$kt]++;
                        }
                    break;
                case 'Estorno':
                        $tabelas['estornada']['data'][$dia-1]++;
                        $tabelas['estornada']['total'][0]++;
                        if($forma){
                            $tabelas['estornada'][$forma][$dia-1]++;
                            $tabelas['estornada']['total'][$kt]++;
                        }
                    break;
                case 'Cancelado':
                case 'Boleto Não Pago':
                        $tabelas['cancelada']['data'][$dia-1]++;
                        $tabelas['cancelada']['total'][0]++; 
                        if($forma){
                            $tabelas['cancelada'][$forma][$dia-1]++;
                            $tabelas['cancelada']['total'][$kt]++;
                        }
                    break;
            }

            $carrinho = $venda->get('ecm_carrinho');

            foreach($carrinho->ecm_carrinho_item as $item){
                if($item->status == 'Adicionado'){
                    $item_produto = $item->get('ecm_produto');
                    $pacoteAltoQi = array_filter($item_produto->get('ecm_tipo_produto'), function($tipo){ return $tipo->id == 58; }); // produto AltoQi
                    $this->EcmVenda->EcmCarrinho->EcmCarrinhoItem->setProductsInCourse($item);

                    if(count($pacoteAltoQi) > 0){
                        $this->EcmVenda->EcmCarrinho->EcmCarrinhoItem->setAppsInPackageAltoQi($item);
                    }
                }
            }
        }
    
        $this->set(compact('ecmVendas', 'dbaVendas', 'ecmVendaStatus', 'ecmTipoPagamento', 'ecmOperadoraPagamento', 'ecmAlternativeHost',  'list_mes', 'datanow', 'tabelas', 'dbaqi'));
        $this->set('_serialize', ['ecmVendas', 'dbaVendas', 'ecmVendaStatus', 'ecmTipoPagamento', 'ecmOperadoraPagamento', 'ecmAlternativeHost','list_mes', 'datanow', 'tabelas', 'dbaqi']);
    }

    /**
     * View method
     *
     * @param string|null $id Ecm Venda id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ecmVenda = $this->EcmVenda->get($id, [
            'contain' => ['EcmTipoPagamento', 'EcmVendaStatus',
                'EcmCarrinho' => ['MdlUser' => ['joinType' => 'LEFT'],
                    'EcmCarrinhoItem' => ['EcmProduto']]]
        ]);

        $this->set('ecmVenda', $ecmVenda);
        $this->set('_serialize', ['ecmVenda']);
    }

    public function vencimentos($id = null)
    {
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmVenda');

        $conditions = [ 'transacao' => ['EcmTransacao.ecm_recorrencia_id IS NULL'],
                        'recorrencia_abertas' => ['EcmRecorrencia.status'=> 1 ],
                        'boleto' => []
                        ];
        $datanow = new \DateTime();

        $tabelas = [ 
                    'pagamentos'    => [ 'dia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0], 'total' => 0 ],

                        'cartao'    => ['dia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'pago' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'aberto' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'cancelado' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'estorno' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'total' => [ 'dia' => 0, 'pago' => 0, 'cancelado' => 0, 'aberto' => 0, 'cancelado' => 0, 'estorno' => 0],
                                            'ids' => [ 'pago' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
                                                        'cancelado' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
                                                        'aberto' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''], 
                                                        'cancelado' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
                                                        'estorno' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','']]],

                    'recorrencia'   => ['dia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        'pago' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        'aberto' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        'cancelado' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                        'estorno' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'total' => [ 'dia' => 0, 'pago' => 0, 'cancelado' => 0, 'aberto' => 0, 'cancelado' => 0, 'estorno' => 0],
                                            'ids' => [ 'pago' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
                                                       'cancelado' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
                                                       'aberto' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''], 
                                                       'cancelado' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
                                                       'estorno' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','']]],
                    'boleto'   => ['dia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'pago' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'aberto' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'cancelado' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'estorno' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'total' => [ 'dia' => 0, 'pago' => 0, 'cancelado' => 0, 'aberto' => 0, 'cancelado' => 0, 'estorno' => 0],
                                            'ids' => [ 'pago' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
                                                        'cancelado' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
                                                        'aberto' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''], 
                                                        'cancelado' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
                                                        'estorno' => ['','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','']]       
                                    ]

                    ];


   
                $tabela_valores = [
                        'dias'    => [ 'dia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0], 'total' => 0 ],
                        'pagamentos'   => [
                                            'dia' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'parcelados' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'avista' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                                            'total' => [ 'dia' => 0, 'parcelados' => 0, 'avista' => 0 ]
                                        ]
                ];                

        $list_mes = $this->EcmRecorrencia->find('list', [
            'keyField' => function($e){
                return $e->get('data_envio')->format('m/Y');
            },
            'valueField' => function($e){
                return $e->get('data_envio')->format('m/Y');
            },
            'groupField' => function ($e) {
                return $e->get('data_envio')->format('Y');
            }
        ])->order(['year(data_envio)' => 'DESC', 'month(data_envio)' => 'DESC'])->toArray();


        if($this->request->is('post')) {

            if (strlen(trim($this->request->data('mes'))) > 0)
                $datanow = \DateTime::createFromFormat('m/Y', $this->request->data('mes'));

                $year = $datanow->format('Y');
                $month = $datanow->format('n');
                $ndias = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $dataInicio = clone $datanow;
                $dataFim = clone $datanow;
                $dataInicio->setDate($year, $month, 1);
                $dataInicio->setTime(0, 0, 0);
                $dataFim->setDate($year, $month, $ndias);
                $dataFim->setTime(23, 59, 59);

                $conditions['boleto']['EcmVendaBoleto.data >='] = $dataInicio->format('Y-m-d H:i:s');
                $conditions['boleto']['EcmVendaBoleto.data <='] = $dataFim->format('Y-m-d H:i:s');

                $period = new \DateInterval('P12M');
                $dataInicio->sub($period);
                $conditions['recorrencia_abertas']['EcmRecorrencia.data_envio >='] = $dataInicio->format('Y-m-d H:i:s');
                $conditions['recorrencia_abertas']['EcmRecorrencia.data_envio <='] = $dataFim->format('Y-m-d H:i:s');

                array_push($conditions['transacao'], 'FROM_UNIXTIME(UNIX_TIMESTAMP(EcmTransacao.data_envio), "%Y-%m") <= "'.$datanow->format('Y-m'). '"');
                array_push($conditions['transacao'], 'FROM_UNIXTIME( UNIX_TIMESTAMP( STR_TO_DATE( concat( PERIOD_ADD( concat( YEAR( EcmTransacao.data_envio ),DATE_FORMAT( EcmTransacao.data_envio, "%m")), ( EcmVenda.numero_parcelas -1)),DATE_FORMAT( EcmTransacao.data_envio, "%d")), "%Y%m%d")), "%Y-%m") >= "'.$datanow->format('Y-m'). '"');
    
                $conditions['transacao']['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 2, 'EcmTransacao.ecm_transacao_status_id in' => [7,5]];
                $conditions['transacao']['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 4, 'EcmTransacao.ecm_transacao_status_id in' => [1,13,14]];
        }else{
            $datanow->setTime(0, 0, 0);
            $datanow->setDate($datanow->format('Y'), $datanow->format('n'), 1);
            $year = $datanow->format('Y');
            $month = $datanow->format('n');
            $ndias = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $dataInicio = clone $datanow;
            $dataFim = clone $datanow;
            $dataInicio->setDate($year, $month, 1);
            $dataInicio->setTime(0, 0, 0);
            $dataFim->setDate($year, $month, $ndias);
            $dataFim->setTime(23, 59, 59);
            $conditions['boleto']['EcmVendaBoleto.data >='] = $dataInicio->format('Y-m-d H:i:s');
            $conditions['boleto']['EcmVendaBoleto.data <='] = $dataFim->format('Y-m-d H:i:s');

            $period = new \DateInterval('P12M');
            $dataInicio->sub($period);
            $conditions['recorrencia_abertas']['EcmRecorrencia.data_envio >='] = $dataInicio->format('Y-m-d H:i:s');
            $conditions['recorrencia_abertas']['EcmRecorrencia.data_envio <='] = $dataFim->format('Y-m-d H:i:s');
            
            array_push($conditions['transacao'], 'FROM_UNIXTIME(UNIX_TIMESTAMP(EcmTransacao.data_envio), "%Y-%m") <= "'.$datanow->format('Y-m'). '"');
            array_push($conditions['transacao'], 'FROM_UNIXTIME( UNIX_TIMESTAMP( STR_TO_DATE( concat( PERIOD_ADD( concat( YEAR( EcmTransacao.data_envio ),DATE_FORMAT( EcmTransacao.data_envio, "%m")), ( EcmVenda.numero_parcelas -1)),DATE_FORMAT( EcmTransacao.data_envio, "%d")), "%Y%m%d")), "%Y-%m") >= "'.$datanow->format('Y-m'). '"');

            $conditions['transacao']['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 2, 'EcmTransacao.ecm_transacao_status_id in' => [7,5]];
            $conditions['transacao']['OR'][] = ['EcmTipoPagamento.ecm_forma_pagamento_id' => 4, 'EcmTransacao.ecm_transacao_status_id in' => [1,13,14]];

        }

        $transacoes = $this->EcmTransacao->find('all')
                                            ->select(['EcmTransacao.id', 'EcmVenda.numero_parcelas', 'EcmTransacao.data_envio', 'EcmTransacao.ecm_transacao_status_id', 'EcmTipoPagamento.ecm_forma_pagamento_id', 'EcmTipoPagamento.id', 'EcmFormaPagamento.controller',
                                                      'vencimento' => 'FROM_UNIXTIME(UNIX_TIMESTAMP(STR_TO_DATE(concat(PERIOD_ADD(concat(YEAR(EcmTransacao.data_envio),DATE_FORMAT( EcmTransacao.data_envio, "%m")), (EcmVenda.numero_parcelas-1)),DATE_FORMAT( EcmTransacao.data_envio, "%d")), "%Y%m%d")), "%Y-%m-%d %T") '])
                                            ->select($this->EcmVenda)
                                            ->contain(['MdlUser','EcmTipoPagamento' => ['EcmFormaPagamento'],'EcmVenda'])
                                            ->where($conditions['transacao']);

        $recorrencia_abertas = $this->EcmRecorrencia->find()
                                                    ->contain(['MdlUser', 'EcmTipoPagamento' => ['EcmFormaPagamento'], 
                                                                    'EcmOperadoraPagamento', 'EcmVenda', 'EcmTransacao'])
                                                    ->where($conditions['recorrencia_abertas']);

        $boletos = $this->EcmVenda->EcmVendaBoleto->find('all')
                                                ->select(['EcmVendaBoleto.id',  'EcmVendaBoleto.parcela', 'EcmVendaBoleto.data', 'EcmVendaBoleto.status'])
                                                ->where($conditions['boleto']);

        $vendas = $this->EcmVenda->find('all')
                                 ->contain(['MdlUser', 'EcmCarrinho', 'EcmTipoPagamento' => ['EcmFormaPagamento'], 'EcmOperadoraPagamento',
                                             'EcmTransacao' => function ($q) {
                                                                return $q->where(['EcmTransacao.ecm_recorrencia_id IS NULL']);
                                                            }, 
                                             'EcmRecorrencia' => ['EcmTransacao'], 'EcmVendaBoleto'])
                                 ->where(['EcmVenda.ecm_venda_status_id' => 2, 
                                           'FROM_UNIXTIME(UNIX_TIMESTAMP(EcmVenda.data), "%Y-%m") <= "'.$datanow->format('Y-m'). '"',
                                           'FROM_UNIXTIME( UNIX_TIMESTAMP( STR_TO_DATE( concat( PERIOD_ADD( concat( YEAR( EcmVenda.data ),DATE_FORMAT( EcmVenda.data, "%m")), ( EcmVenda.numero_parcelas -1)),DATE_FORMAT( EcmVenda.data, "%d")), "%Y%m%d")), "%Y-%m") >= "'.$datanow->format('Y-m'). '"'
                                           ])
                                 ->order(['EcmVenda.data' => 'DESC']);

        foreach ($vendas as $venda) {
            $carrinho = $venda->get('ecm_carrinho');
            $data_vencimento = \DateTime::createFromFormat('Y-m-d', $venda->data->format('Y-m-d'));
            $data_pagamento = \DateTime::createFromFormat('Y-m-d', $venda->data->format('Y-m-d'));
            
            for ($i=1; $i <= $venda->numero_parcelas ; $i++) { 
                $data_pagamento->modify('+1 month');
                $valor = ($i==1)? $carrinho->calcularParcela($venda->numero_parcelas, true) : $carrinho->calcularParcela($venda->numero_parcelas);
                if($data_vencimento->format('Y-m') == $datanow->format('Y-m'))
                    $venda['vencimento'] = [ 'parcela'=> $i, 'valor' => $valor, 'data' => clone $data_vencimento,'data_pagamento' => clone $data_pagamento, 'status' => true ];
                $data_vencimento->modify('+1 month');
            }
        }

        foreach ($boletos as $boleto) {
            $dia = $boleto->data->format('j');
            if($dia > $ndias) $dia = $ndias;
            
            $tabelas['pagamentos']['dia'][$dia-1]++;
            $tabelas['pagamentos']['total']++;
            $tabelas['boleto']['dia'][$dia-1]++;
            $tabelas['boleto']['total']['dia']++;

            if($boleto->status == 'nPago'){
                $tabelas['boleto']['pago'][$dia-1]++;
                $tabelas['boleto']['total']['pago']++;
                $tabelas['boleto']['ids']['pago'][$dia-1] .= $boleto->id.',';
            }else if($boleto->status == 'Pago'){
                $tabelas['boleto']['cancelado'][$dia-1]++;
                $tabelas['boleto']['total']['cancelado']++;
                $tabelas['boleto']['ids']['cancelado'][$dia-1] .= $boleto->id.',';
            }else if($boleto->status == 'Em aberto'){
                $tabelas['boleto']['aberto'][$dia-1]++;
                $tabelas['boleto']['total']['aberto']++;
                $tabelas['boleto']['ids']['aberto'][$dia-1] .= $boleto->id.',';
            }
        }

        foreach ($transacoes as $trans) {

            $dia = $trans->data_envio->format('j');
            if($dia > $ndias) {
                $dia = $ndias;
                $tabelas['cartao']['aberto'][$dia-1]++;
                $tabelas['cartao']['total']['aberto']++;
                $tabelas['cartao']['ids']['aberto'][$dia-1] .= $trans->id.',';
            }else{
                if($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller == 'SuperPayV3' ){
                    $trans->status = $trans->getStatusV3($trans->ecm_transacao_status_id);
                }else if($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller == 'SuperPay'){
                    $trans->status = $trans->getStatusV1($trans->ecm_transacao_status_id);
                }

                if($trans->status){
                    switch ($trans->status) {
                        case 'paga':
                                $tabelas['cartao']['pago'][$dia-1]++;
                                $tabelas['cartao']['total']['pago']++;
                                $tabelas['cartao']['ids']['pago'][$dia-1] .= $trans->id.',';
                            break;      
                        case 'estorno':
                            $tabelas['cartao']['estorno'][$dia-1]++;
                            $tabelas['cartao']['total']['estorno']++;
                            $tabelas['cartao']['ids']['estorno'][$dia-1] .= $trans->id.',';
                        break;
                        default:
                            $tabelas['cartao']['cancelado'][$dia-1]++;
                            $tabelas['cartao']['total']['cancelado']++;
                            $tabelas['cartao']['ids']['cancelado'][$dia-1] .= $trans->id.',';
                        break;
                    }
                }
            }
            $tabelas['pagamentos']['dia'][$dia-1]++;
            $tabelas['pagamentos']['total']++;
            $tabelas['cartao']['dia'][$dia-1]++;
            $tabelas['cartao']['total']['dia']++;
        }

        foreach ($recorrencia_abertas as $rec) {

            $dia = $rec->data_primeira_cobranca->format('j');
            $diff = $datanow->diff($rec->data_primeira_cobranca);
            $k = false;
            $parcelas = $rec->quantidade_cobrancas;

            if($dia > $ndias) $dia = $ndias;

            if(
                ($diff->m < ($rec->quantidade_cobrancas -1)) && $rec->data_primeira_cobranca->format('d/m/Y') == $rec->data_envio->format('d/m/Y')
                || 
                (($diff->m < $rec->quantidade_cobrancas) && $rec->data_primeira_cobranca > $rec->data_envio && $rec->data_primeira_cobranca->format('m') == $datanow->format('m') )
                ){

                $tabelas['pagamentos']['dia'][$dia-1]++;
                $tabelas['pagamentos']['total']++;
                $tabelas['recorrencia']['dia'][$dia-1]++;
                $tabelas['recorrencia']['total']['dia']++;
    
                foreach ($rec->ecm_transacao as $key => $trans) {
                    if(isset($trans->data_cobranca) && $datanow->format('m') == $trans->data_cobranca->format('m')){
                        $k = $key;
                    }
                }

                if($k !== false){

                    $trans = $rec->ecm_transacao[$k];
                    $trans->status = $trans->getStatusV3($trans->ecm_transacao_status_id);
    
                    if($trans->status){
                        switch ($trans->status) {
                            case 'paga':
                                    $tabelas['recorrencia']['pago'][$dia-1]++;
                                    $tabelas['recorrencia']['total']['pago']++;
                                    $tabelas['recorrencia']['ids']['pago'][$dia-1] .= $rec->id.',';
                                break;
                            case 'aguardando_capturar':
                                $tabelas['recorrencia']['aberto'][$dia-1]++;
                                $tabelas['recorrencia']['total']['aberto']++;
                                $tabelas['recorrencia']['ids']['aberto'][$dia-1] .= $rec->id.',';
                            break;   
                            case 'cancelada':
                                $tabelas['recorrencia']['cancelado'][$dia-1]++;
                                $tabelas['recorrencia']['total']['cancelado']++;
                                $tabelas['recorrencia']['ids']['cancelado'][$dia-1] .= $rec->id.',';
                            break;        
                            case 'estorno':
                                $tabelas['recorrencia']['estorno'][$dia-1]++;
                                $tabelas['recorrencia']['total']['estorno']++;
                                $tabelas['recorrencia']['ids']['estorno'][$dia-1] .= $rec->id.',';
                            break;
                            case 'erro':
                            case 'negada':
                                $tabelas['recorrencia']['cancelado'][$dia-1]++;
                                $tabelas['recorrencia']['total']['cancelado']++;
                                $tabelas['recorrencia']['ids']['cancelado'][$dia-1] .= $rec->id.',';
                            break;
                        }
                    }
                }else{
                    $tabelas['recorrencia']['ids']['aberto'][$dia-1] .= $rec->id.',';
                    $tabelas['recorrencia']['aberto'][$dia-1]++;
                    $tabelas['recorrencia']['total']['aberto']++;
                }
            }

        }

        $this->set(compact('list_mes', 'datanow', 'tabelas', 'vendas'));
        $this->set('_serialize', ['list_mes','datanow', 'tabelas', 'vendas']);

    }
    
    /*
    curl 'https://homologacao.superpay.com.br/checkout/api/v2/recorrencia/1428326443195/10285'
     -u MNTECNOLOGIA:restMN1041 -H 'content-encoding: UTF-8' -H 'content-type: application/json'
     -H 'usuario: { "login":"superpay","senha":"superpay"}'
    
    
    public function atualizarVencimentos()
    {

        // Turn off output buffering
        ini_set('output_buffering', 'off');
        // Turn off PHP output compression
        ini_set('zlib.output_compression', false);

        ini_set('max_execution_time', '180000');
                
        //Flush (send) the output buffer and turn off output buffering
        //ob_end_flush();
        while (@ob_end_flush());
                
        // Implicitly flush the buffer(s)
        ini_set('implicit_flush', true);
        ob_implicit_flush(true);
        
        //prevent apache from buffering it for deflate/gzip

        $this->response->type('text/plain');
        $data = new \DateTime();
        echo $data->format( "d/m/Y H:i:s" );
        echo '<br> ### START - PROCESSO ### <br>';
        ob_start(); 

        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmVenda');

        $LINK_PRODUCAO = 'https://superpay2.superpay.com.br/checkout/api/v2/recorrencia';
        $LINK_HOMOLOGACAO = 'https://homologacao.superpay.com.br/checkout/api/v2/recorrencia';
        $ambienteProducao = 1;

        if($ambienteProducao == 1){
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'estabelecimento_qisat_super_pay'])->first()->valor;
            $login =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'usuario_super_pay'])->first()->valor;
            $senha =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'senha_super_pay'])->first()->valor;
            $this->host = $LINK_PRODUCAO;
            $urlTransacao  = 'https://superpay2.superpay.com.br/checkout/api/v2/transacao/';
        }else{
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_estabelecimento_qisat_super_pay'])->first()->valor;
            $login = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_usuario_super_pay'])->first()->valor;
            $senha = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_senha_super_pay'])->first()->valor;
            $this->host = $LINK_HOMOLOGACAO;
            $urlTransacao = 'https://homologacao.superpay.com.br/checkout/api/v2/transacao/';
        }

        $this->auth = json_encode([ "login" => $login, "senha" => $senha ]);

        $recorrencias = $this->EcmRecorrencia->find('all')->where(['id in' => [10011,10012,10013,10014,10025,10026,10027,10029,10030,10031,10032,10033,10034]])->toList();

        $http = new Client(['type' => 'json', 'headers' => array( 'usuario' => $this->auth )]);
        
        foreach ($recorrencias as $recorrencia_old) {

            echo '<br> >> START Recorrência: '.$recorrencia_old['id'];
            $response = false;
            $qtd = 12;
            
            // $recorrencia_old['status'] = ($recorrencia_old['transacao_status'] == '1') ? 1 : 0;
            // unset($recorrencia_old['transacao_status']);
            // unset($recorrencia_old['numero_cobranca_total']);
            // unset($recorrencia_old['codigo_transacao_operadora']);
            // unset($recorrencia_old['autorizacao']);
            // unset($recorrencia_old['mensagem']);
            // unset($recorrencia_old['teste']);
            // unset($recorrencia_old['erro']);
            // unset($recorrencia_old['data_campainha']);
            // unset($recorrencia_old['data_aprovacao_operadora']);

            // if($recorrencia_old['data_envio'])
            //     $recorrencia_old['data_envio'] = \DateTime::createFromFormat('Y-m-d H:i:s', $recorrencia_old['data_envio']);

            // if($recorrencia_old['data_retorno'])
            //     $recorrencia_old['data_retorno'] = \DateTime::createFromFormat('Y-m-d H:i:s', $recorrencia_old['data_retorno']);

            // if($recorrencia_old['data_primeira_cobranca'])
            //     $recorrencia_old['data_primeira_cobranca'] = \DateTime::createFromFormat('Y-m-d H:i:s', $recorrencia_old['data_primeira_cobranca']);

            // $recorrencia = $this->EcmRecorrencia->newEntity($recorrencia_old);

            try {
               // if($this->EcmRecorrencia->save($recorrencia))
                    echo ' <br> - Recorrencia SALVA ';
                // else{
                //     echo '<br> xxx ERROR Recorrência: ';
                //     print_r($recorrencia->errors());
                // }
            } catch (\Exception $e) {
                echo ' <br> xxx FALHA ao salvar Recorrencia: Mensagem ('.$e->getMessage().')';
            }

            try {

                $response = $http->get( $this->host.'/'.$this->estabelecimento.'/'.$recorrencia->id);
                $result = $response->json;  

            } catch (\Exception $e) {
                echo '<br> xxx FALHA NA REQUISIÇÃO!  ID:'.$recorrencia->id.' '.$e->getMessage();
            }

            if($response && $response->isOk()){
                
                if($result['recorrencia']){
                    $qtd = $result['recorrencia']['numeroCobrancaTotal'] - $result['recorrencia']['numeroCobrancaRestantes'];
                    $recorrencia->set('numero_cobranca_restantes', $result['recorrencia']['numeroCobrancaRestantes']);

                    if($result['recorrencia']['numeroCobrancaRestantes'] == 0)
                        $recorrencia->set('status', 0);

                    if($recorrencia->status && $qtd > 0){
                        $diff = $data->diff($recorrencia->data_primeira_cobranca);

                        if((($diff->format('%y') * 12) + $diff->format('%m')) > $recorrencia->quantidade_cobrancas)
                            $recorrencia->set('status', 0);
                    }

                    // $this->EcmRecorrencia->save($recorrencia);

                    echo ' - UPDATE OK';
                }else{
                    echo '<br> - Sem Recorrência no retorno!';
                }

            }else if($response->code == 404){
                echo ' <br> - NÃO ENCONTRADA!';
            }else {
                echo ' <br> xxx FALHA NO RETORNO DA REQUISIÇÃO! CODE('.$response->code.')';
            }


            $transacoes = $this->EcmTransacao->find('all')->where(['ecm_recorrencia_id' => $recorrencia_old->id, 'data_cobranca is null', 'ecm_transacao_status_id' => 1]);

            foreach ($transacoes as $transacao) {

                // $idTransacao = $recorrencia->id.'00'.$i;
                echo '<br> - START Transação: '.$transacao->id;

                $urlTransacao2 = $urlTransacao.$this->estabelecimento.'/'.$transacao->id_integracao;
                $resp = $http->get( $urlTransacao2 );
                $resultTransacao = $resp->json; 
                $data_cobranca = false;

                if($resp->isOk() || $resultTransacao){

                        // $transacao = $this->EcmTransacao->newEntity();
                        // $transacao->set('id_integracao', $idTransacao );
                        // $transacao->set('parcela', $i);
                        // $transacao->set('estabelecimento', $this->estabelecimento);

                        // $transacao->set('ecm_recorrencia_id', $recorrencia->id);
                        // $transacao->set('descricao', 'Transação da Recorrência');
                        // $transacao->set('mdl_user_id', $recorrencia['mdl_user_id']);
                        // $transacao->set('valor', $recorrencia['valor']);
                        // $transacao->set('ecm_tipo_pagamento_id', $recorrencia['ecm_tipo_pagamento_id']);
                        // $transacao->set('ecm_operadora_pagamento_id', $recorrencia['ecm_operadora_pagamento_id']);
                        // $transacao->set('ecm_venda_id', $recorrencia['ecm_venda_id']);
                        // $transacao->set('ip', $recorrencia['ip']);

                        // $status = (array_key_exists('statusTransacao', $resultTransacao)) ? $resultTransacao['statusTransacao'] : false;
                        // $transacao->set('ecm_transacao_status_id', $resultTransacao['statusTransacao']);

                        if(array_key_exists('numeroTransacao', $resultTransacao) && ($transacao->ecm_transacao_status_id == '1' || $transacao->ecm_transacao_status_id == '3' || $transacao->ecm_transacao_status_id == '13')){
                            
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

                        }else if($transacao->ecm_transacao_status_id == '9' || $transacao->ecm_transacao_status_id == '5'){
                            if(array_key_exists('dataAprovacaoOperadora', $resultTransacao)){
                                $data_cobranca = \DateTime::createFromFormat('d/m/Y', $resultTransacao['dataAprovacaoOperadora']);
                                $data_cobranca->setTime(0, 0, 0);
                                $transacao->set('data_cobranca', $data_cobranca);
                            }
                        }else if($transacao->ecm_transacao_status_id){
                            print_r($resultTransacao);
                        }

                        if($data_cobranca){
                            $transacao->set('data_envio', $data_cobranca);
                            $transacao->set('data_retorno', $data_cobranca);
                        }else{
                            $transacao->set('data_envio', $recorrencia_old->data_envio);
                            $transacao->set('data_retorno', $recorrencia_old->data_retorno);
                        }

                    try {
                    
                        if($transacao->ecm_transacao_status_id){
                            //if($this->EcmTransacao->save($transacao))
                                echo ' - SALVAR OK <br>';
                                // print_r($transacao);
                                print_r($resultTransacao);
                            // else{
                            //     echo '<br> xxx ERROR Transação: ';
                            //     print_r($recorrencia->errors());
                            // }
                        }else{
                            echo ' - SALVAR NOT ';
                        }

                    } catch (\Exception $e) {
                        echo ' <br> - FALHA ao salvar Transação:'.$idTransacao . ' Mensagem ('.$e->getMessage().')';
                    }
                }

                ob_flush();
                flush();
                echo ' - END ';
            }

            echo '<br> << END - Recorrência <br>';

            ob_flush();
            flush();
        }

        echo '<br> ### END - PROCESSO ### <br>';
        $data = new \DateTime();
        echo $data->format( "d/m/Y H:i:s" );
        ob_end_flush();

        die;
        $this->set(compact('results'));
        $this->set('_serialize', ['results']);
    }
    */

    /*
    public function atualizarVencimentosTop()
    {

        // Turn off output buffering
        ini_set('output_buffering', 'off');
        // Turn off PHP output compression
        ini_set('zlib.output_compression', false);

        ini_set('max_execution_time', '180000');
                
        //Flush (send) the output buffer and turn off output buffering
        //ob_end_flush();
        while (@ob_end_flush());
                
        // Implicitly flush the buffer(s)
        ini_set('implicit_flush', true);
        ob_implicit_flush(true);
        
        //prevent apache from buffering it for deflate/gzip

        $this->response->type('text/plain');
        $data = new \DateTime();
        echo $data->format( "d/m/Y H:i:s" );
        echo '<br> <strong> ### START - PROCESSO ### </strong> <br>';
        ob_start(); 

        $this->loadModel('Configuracao.EcmConfig');
        $this->loadModel('Carrinho.EcmRecorrencia');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmTransacao');
        $this->loadModel('Carrinho.EcmVenda');
        $this->loadModel('FormaPagamento.EcmTipoPagamento');
        $this->loadModel('FormaPagamento.EcmOperadoraPagamento');

        
        $LINK_PRODUCAO = 'https://superpay2.superpay.com.br/checkout/api/v2/recorrencia';
        $LINK_HOMOLOGACAO = 'https://homologacao.superpay.com.br/checkout/api/v2/recorrencia';
        $ambienteProducao = 1;

        if($ambienteProducao == 1){
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'estabelecimento_qisat_super_pay'])->first()->valor;
            $login =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'usuario_super_pay'])->first()->valor;
            $senha =  $this->EcmConfig->find()->where(['EcmConfig.nome' => 'senha_super_pay'])->first()->valor;
            $this->host = $LINK_PRODUCAO;
            $urlTransacao  = 'https://superpay2.superpay.com.br/checkout/api/v2/transacao/';
        }else{
            $this->estabelecimento = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_estabelecimento_qisat_super_pay'])->first()->valor;
            $login = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_usuario_super_pay'])->first()->valor;
            $senha = $this->EcmConfig->find()->where(['EcmConfig.nome' => 'teste_senha_super_pay'])->first()->valor;
            $this->host = $LINK_HOMOLOGACAO;
            $urlTransacao = 'https://homologacao.superpay.com.br/checkout/api/v2/transacao/';
        }

        $this->auth = json_encode([ "login" => $login, "senha" => $senha ]);
        $http = new Client(['type' => 'json', 'headers' => array( 'usuario' => $this->auth )]);

        //  ConnectionManager::config('dbaqi', ['url' => 'Dblib://192.168.10.6/DBAQI?username=MNTEC&password=J3ch5l']);
        ConnectionManager::config('dbaqi', ['url' => 'Dblib://192.168.100.160/DBAQI?username=qisat&password=qisat.123']);
        $this->dbaqi = ConnectionManager::get('dbaqi');

        $vendas = $this->EcmVenda->find('all')
                                 ->contain([ 'EcmTipoPagamento' => ['EcmFormaPagamento'],
                                            'EcmOperadoraPagamento', 'EcmVendaStatus',
                                            'EcmCarrinho' => ['EcmCarrinhoItem'],
                                            'EcmRecorrencia' => [ 'EcmTransacao'  => ['EcmTipoPagamento' => ['EcmFormaPagamento']] ],
                                            'EcmTransacao' => ['EcmTipoPagamento' => ['EcmFormaPagamento']]
                                            ])

                                // ->matching('EcmTransacao',  function ($q) {
                                //     return $q ->contain(['EcmTipoPagamento' => ['EcmFormaPagamento']])
                                //                 ->where(['EcmTransacao.ecm_recorrencia_id IS NULL'])
                                //                 ->order(['EcmTransacao.data_envio' => 'desc']);
                                //     })
                                    
                                //  ->matching('EcmRecorrencia',  function ($q) {
                                //     return $q ->contain(['EcmTransacao' => ['EcmTipoPagamento' => ['EcmFormaPagamento']] ])
                                //                 ->where(['NOT EcmRecorrencia.id IS NULL'])
                                //                 ->order(['EcmTransacao.data_envio' => 'desc']);
                                //  })
                                 ->where(['EcmVenda.ecm_venda_status_id in' => [2,1], 'NOT EcmVenda.pedido IS NULL'])
                                //  ->where(['EcmVenda.id in' => [5670,5584,5516,5510,5463,5411,5410,5379,5245,5243,5235,5136,5018,5013,4989,4960,4935,4927,4926,4874,4722]])

                                // ->where(['EcmVenda.id' => 5096])

                               // ->where([])

                                 ->order(['EcmVenda.data' => 'DESC'])
                                 ->group(['EcmVenda.id'])
                                //  ->limit(1000)
                                 ->toArray();

        $update = [ 'dbaqi' => [], 'eco' => []];

        foreach ($vendas as $venda) {

            echo '<br>>> START VENDA: <strong>'.$venda->id .' </strong> Pedido: <strong>'.$venda->pedido;

            $carrinho = $venda->get('ecm_carrinho');

            $vencimentos = $this->dbaqi->execute('SELECT p.codigo, p.idStatusDoPedido,p.NumeroPedidoQiSat, pv.NumeroAutorizacaoCartao,
                                                        pv.NroParcela, pv.DATAVCTO, pv.dataPgto, pv.PagamentoRecorrente, 
                                                        pv.IdFormaPagamento, pv.NroBloqueto, pv.pago, pv.ValorPago, pv.ObservacaoVencimento, pv.CodigoDoRegistro
                                                        FROM Pedido p
                                                    LEFT JOIN PedidoVencimentos pv ON pv.IDPEDIDO = p.codigo
                                                    WHERE p.NumeroPedidoQiSat = ?', [$venda->pedido])->fetchAll('assoc');

            $proposta = null;
            $tabela_valores = [ 'venc' => [], 'venc_top' => [], 'venc_eco' => []];
            $data_compra = \DateTime::createFromFormat('Y-m-d', $venda->data->format('Y-m-d')); 
            $data_venc = clone $data_compra;
            $datanow = new \DateTime();
            $data_venc->setTime(0, 0, 0);
            $valor_p1 = $carrinho->calcularParcela($venda->numero_parcelas, true);
            $valores = $carrinho->calcularParcela($venda->numero_parcelas);
            $total = $carrinho->calcularTotal();
            $n_venc = count($vencimentos);

            ######## CREATE VENCIMENTOS ##############
            for ($i=1; $i <= $venda->numero_parcelas ; $i++) {
                $valor = ($i==1) ? $valor_p1 : $valores;
                $nro_parcela = $i.'/'.$venda->numero_parcelas;
                $tabela_valores['venc'][$nro_parcela] = [ 'parcela'=> $nro_parcela, 'valor' => $valor, 'data_venc' => clone $data_venc ];
                $data_venc->modify('+1 month');
            }
            ######## CREATE VENCIMENTOS ##############

            ######## VENCIMENTOS DBQAI ##############
            if($n_venc > 0){
                foreach ($vencimentos as $venc) {
                    $proposta = $venc['codigo'];
                    $parcelas = explode("/", $venc['NroParcela']);
                    $nro_parcela = (count($parcelas) > 1) ? $parcelas[0].'/'.$parcelas[1] : $parcelas[0].'/'.$venda->numero_parcelas;

                    $tabela_valores['venc_top'][$venc['CodigoDoRegistro']] = $venc;
                    $tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['NroParcela_check'] = $nro_parcela;
                    $tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['parcela'] = $parcelas[0];

                    if($n_venc == $venda->numero_parcelas && $venc['IdFormaPagamento'] != 1){ // REFATURADO
                        if(isset($tabela_valores['venc'][$nro_parcela]['data_venc']) && $tabela_valores['venc'][$nro_parcela]['data_venc']->format("Y-m-d H:i:s") != $venc['DATAVCTO'] )
                            $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['DATAVCTO'] = $tabela_valores['venc'][$nro_parcela]['data_venc']->format("Y-m-d H:i:s");
                    }else{
                        echo '#NOT-UPDATE#';
                    }
                }
            }else{
                for ($i=1; $i <= $venda->numero_parcelas ; $i++) {
                    $tabela_valores['venc_top'][$i] = false;
                }
            }
            ######## VENCIMENTOS DBQAI ##############

            echo ' </strong> Proposta:  <strong>'.((is_null($venda->proposta)) ? $proposta : $venda->proposta ).' </strong> <strong> Parcelas '.$venda->numero_parcelas.' X '. Number::format($venda->valor_parcelas, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']) .' Total:'.Number::format($total, ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ ']).'</strong> Data: '. $venda->data->format( "d/m/Y H:i:s" ). ' TipoPagamento: ';

            if( isset($proposta) && is_null($venda->proposta))
                $update['eco'][$venda->id] = ['proposta' => (int)$proposta ];

            if($venda->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->tipo == 'cartao'){
                echo 'Cartão';

                foreach ($venda->get('ecm_transacao') as $trans) {
                    $status = $trans->getStatus($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller);

                    if($status == 'paga'){

                        $n_p = 1;
                        $parcelas = $venda->numero_parcelas;
                        $nro_parcela = $n_p.'/'.$parcelas;
                        $valor = $carrinho->calcularParcela($venda->numero_parcelas, true);
                        $data_envio = \DateTime::createFromFormat('Y-m-d', $trans->data_envio->format('Y-m-d'));  
                        $data_envio->setTime(0, 0, 0);
                        $data_paga = clone $data_envio;
                        $data_venc = clone $data_envio;
                        $data_paga->modify('+1 month');
                        $data_paga->modify('+2 day');

                        for(;$n_p<=$parcelas;$n_p++){
                            $nro_parcela = $n_p.'/'.$parcelas;
                            array_push($tabela_valores['venc_eco'], [ 'nro_parcela' =>  $nro_parcela , 'transacao_n'=> $n_p, 'aut' => $trans->arp, 'valor' => $valor, 'data_venc' => clone $data_venc, 'data_paga' => clone $data_paga, 'status' => $status]);

                            if(isset($proposta)){

                                $venc = array_filter(
                                    $tabela_valores['venc_top'],
                                    function ($v) use ($nro_parcela) {
                                        return $v['NroParcela_check'] == $nro_parcela;
                                    }
                                );

                                $venc = (count($venc) > 0) ? reset($venc) : false;

                                if($venc && $venc['IdFormaPagamento'] != 1){ // REFATURADO

                                    $up = false;

                                    if($data_paga->format("Y-m-d H:i:s") != $venc['dataPgto'])
                                        $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['dataPgto'] = $data_paga->format("Y-m-d H:i:s");

                                    if($tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['pago'] == 0){
                                        $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['pago'] = 1;
                                        $up = true;
                                    }

                                    if($tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['NumeroAutorizacaoCartao'] == ''){
                                        $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['NumeroAutorizacaoCartao'] = $trans->arp;
                                        $up = true;
                                    }

                                    if($tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['ValorPago'] != $valor){
                                        $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['ValorPago'] = number_format($valor, 2,'.', '');
                                        $up = true;
                                    }

                                    if($up){
                                        if($tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['ObservacaoVencimento'] == '')
                                            $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['ObservacaoVencimento'] = '#AtualizacaoIntegracaoEcommerce';
                                        else
                                            $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['ObservacaoVencimento'] = $tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['ObservacaoVencimento'].' #AtualizacaoIntegracaoEcommerce';
                                    }
                                }
                            }

                            if($n_p == 1){
                                $data_paga->modify('-2 day');
                                $valor = $carrinho->calcularParcela($venda->numero_parcelas);
                            }

                            $data_paga->modify('+1 month');
                            $data_venc->modify('+1 month');
                        }
                    }
                }

            }else if($venda->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->tipo == 'cartao_recorrencia'){
                echo 'recorrencia';
                $total_rec = count($venda->get('ecm_recorrencia'));
                echo ' Total rec:' .$total_rec;

                foreach ($vencimentos as $venc) {
                    if(isset($proposta) && $tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['PagamentoRecorrente'] == 0 && $venc['IdFormaPagamento'] != 1) // REFATURADO
                        $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['PagamentoRecorrente'] = 1;
                }

                $n_p = 1;
                $r = 1;
                $parcelas = $venda->numero_parcelas;
                $nro_parcela = $n_p.'/'.$parcelas;
                $rec_pago = [];

                array_map(function($r) use (&$rec_pago){ 
                    $p = false;
                    array_map(function($t) use (&$p){
                        $s = $t->getStatus($t->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller); 
                            if($s=='paga')
                                $p=true;
                    }, $r->get('ecm_transacao'));

                    if($p) $rec_pago[$r->id] = false; 
                }, $venda->get('ecm_recorrencia'));

                foreach ($venda->get('ecm_recorrencia') as $rec) {
                    $transacoes = $rec->get('ecm_transacao');
                    $pago = false;
                    array_map(function($trans) use (&$pago){ 
                                    $status = $trans->getStatus($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller); 
                                        if($status=='paga')
                                            $pago = true; 
                                }, $transacoes);

                    if($total_rec > 1 && $pago && count($rec_pago) > 1)
                        echo ' <br> ######## Rid: '.$rec->id.' Transações:' .count($transacoes). ' Data Envio: '. (!is_null($rec->data_envio) ? $rec->data_envio->format('Y-m-d H:i:s') : ''). ' Primeira Cobrança:'. ( (!is_null($rec->data_primeira_cobranca)) ? $rec->data_primeira_cobranca->format('Y-m-d H:i:s') : '');

                    if($pago){

                        if(count($rec_pago) > 1)
                            echo '<br> total cobrança: '.$rec->quantidade_cobrancas . ' restantes: '.$rec->numero_cobranca_restantes;

                        foreach ($transacoes as $trans) {
                            $status = $trans->getStatus($trans->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->controller);

                            if(count($rec_pago) > 1)
                                echo '<br> >>>>>>>> Parcela: '.$trans->parcela. ' Status: '.$status.' Data envio:'. ((!is_null($trans->data_envio)) ? $trans->data_envio->format('Y-m-d') : '').' Data Retorno: '. ((!is_null($trans->retorno)) ?  $trans->retorno->format('Y-m-d') : ''). ' Data Cobrança: '.((!is_null($trans->data_cobranca)) ? $trans->data_cobranca->format('Y-m-d') : '');

                            if($status == 'paga'){
                                $data_envio = (isset($trans->data_cobranca)) ? \DateTime::createFromFormat('Y-m-d', $trans->data_cobranca->format('Y-m-d')) : \DateTime::createFromFormat('Y-m-d', $trans->data_envio->format('Y-m-d'));
                            }else{
                                $data_envio = \DateTime::createFromFormat('Y-m-d', $trans->data_envio->format('Y-m-d'));  
                            }
                            
                            $data_envio->setTime(0, 0, 0);
                            $data_paga = clone $data_envio;
                            $data_venc = clone $data_envio;
                            $data_paga->modify('+1 month');
                            $data_paga->modify('+2 day');

                            if( $r > 1 && $rec_pago[$rec->id] == false ){
                                $rec_pago[$rec->id] = true; 
                                $n_p = $parcelas - ($rec->quantidade_cobrancas -1);
                                $nro_parcela = $n_p.'/'.$parcelas;
                            }

                            array_push($tabela_valores['venc_eco'], [ 'nro_parcela' =>$nro_parcela, 'transacao_n'=> $n_p, 'aut' => $trans->arp, 'valor' => $trans->valor, 'data_venc' => clone $data_venc, 'data_paga' => clone $data_paga, 'status' => $status]);
                            
                            if($status == 'paga'){

                                $venc = array_filter(
                                    $tabela_valores['venc_top'],
                                    function ($v) use ($nro_parcela) {
                                        return $v['NroParcela_check'] == $nro_parcela;
                                    }
                                );

                                $venc = (count($venc) > 0) ? reset($venc) : false;

                                if($venc && $venc['IdFormaPagamento'] != 1){ // REFATURADO

                                    if($data_paga->format("Y-m-d H:i:s") != $venc['dataPgto'])
                                        $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['dataPgto'] =  $data_paga->format("Y-m-d H:i:s");

                                    if(isset($proposta)){
                                        
                                        $up = false;

                                        if($tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['pago'] == 0){
                                            $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['pago'] = 1;
                                            $up = true;
                                        }

                                        if($tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['NumeroAutorizacaoCartao'] == ''){
                                            $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['NumeroAutorizacaoCartao'] = $trans->arp;
                                            $up = true;
                                        }

                                        if($tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['ValorPago'] != $trans->valor){
                                            $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['ValorPago'] = number_format($trans->valor, 2,'.', '');
                                            $up = true;
                                        }

                                        if($up){
                                            if($tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['ObservacaoVencimento'] == '')
                                                $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['ObservacaoVencimento'] = '#AtualizacaoIntegracaoEcommerce';
                                            else
                                                $update['dbaqi'][$proposta][$venc['CodigoDoRegistro']]['ObservacaoVencimento'] = $tabela_valores['venc_top'][$venc['CodigoDoRegistro']]['ObservacaoVencimento'].' #AtualizacaoIntegracaoEcommerce';
                                        }
                                    }
                                }
                            }
                            $n_p++;
                            $nro_parcela = $n_p.'/'.$parcelas;
                            $data_paga->modify('-2 day');
                        }
                        $r++;
                    }
                }
                
            }else if($venda->get('ecm_tipo_pagamento')->get('ecm_forma_pagamento')->tipo == 'boleto'){
                echo 'BBOLETO';   

                if( isset($proposta) && $venda->ecm_venda_status_id == 1)
                    $update['eco'][$venda->id]['ecm_venda_status_id'] = 2;
                
                if( is_null($proposta) && (($venda->ecm_venda_status_id == 1) || ($venda->ecm_venda_status_id == 2)))
                    $update['eco'][$venda->id]['ecm_venda_status_id'] = 4;
                
            }

            // #################### PRINT #########################
            echo '<table cellpadding="1" cellspacing="1" border=1>';
                echo ' <thead> <tr>';
                    echo '<td> DATA </td>';
                    echo '<td> Vencimentos TOP </td>';
                    echo '<td> Vencimentos E-Commerce </td>';
                echo '</tr> </thead>';

                $print = [];
                $l = 0;
                $c = 0;
                foreach ($tabela_valores['venc'] as $key => $venc) {
                    $print[$l][$c] = $venc['parcela']. ' - '.$venc['data_venc']->format('d-m-Y');
                    $l++;
                }

                $l = 0;
                $c = 1;
                foreach ($tabela_valores['venc_top'] as $venc) {
                    if($venc){
                        //' rEC:'.(($venc['dados']['PagamentoRecorrente'] == 1) ? 'OK' : ('<span style="color:red">NOT</span>')  )
                        $print[$l][$c] =  $venc['NroParcela'].' - '.  str_replace(" 00:00:00", "", $venc['DATAVCTO']). ' '. $venc['NroBloqueto'] .' FormaID:'. $venc['IdFormaPagamento'] . ' PAGO:'.$venc['pago']. ' ValorPAGO:'.$venc['ValorPago'].  ' Aut.:'.$venc['NumeroAutorizacaoCartao'] ;
                    }else{
                        $print[$l][$c] = ' - ';
                    }
                    $l++;
                }

                $l = 0;
                $c = 2;
                foreach ($tabela_valores['venc_eco'] as $venc) {
                    $print[$l][$c] = $venc['nro_parcela'] .' - '. $venc['transacao_n'] .' - '. Number::format($venc['valor'], ['pattern' => '#.###,00', 'places' => 2, 'before' => 'R$ '])  .' data venc: '.$venc['data_venc']->format('d-m-Y'). ' data paga: '.$venc['data_paga']->format('d-m-Y').  ' STATUS:'.$venc['status']. ' Aut.:' .$venc['aut'];
                    $l++;
                }

                foreach ($print  as $linha) {
                    echo '<tr>';
                    foreach ($linha  as $col) 
                        echo '<td>'.$col. '</td>';
                    echo '</tr> ';
                }

            echo '</table>';

            if(isset($update['dbaqi'][$proposta])){

                echo '<table cellpadding="1" cellspacing="1" border=1>';
                echo ' <thead> <tr>';
                    echo '<td> where </td>';
                    echo '<td> update </td>';
                    echo '<td> dados Origem </td>';
                echo '</tr> </thead>';

                
                foreach ($update['dbaqi'][$proposta] as $k1 => $dados) {
                    echo '<tr>';
                    echo '<td>'.$proposta.' - '. $k1.'</td>';
                    echo '<td>';

                    $data =  $tabela_valores['venc_top'][$k1] ;
                    
                    foreach ($dados as $k2 => $v2){
                        echo ' '.$k2.':'.$v2;
                    }
                    '</td>';

                    unset($data['codigo']);
                    unset($data['idStatusDoPedido']);
                    unset($data['NumeroPedidoQiSat']);

                    echo '<td>';
                    foreach ($data as $k3 => $v3){
                        echo ' '.$k3.':'.$v3;
                    }
                    '</td>';
                    
                    echo '</tr> ';
                }
                echo '</table>';
            }

            ob_flush();
            flush();
            // #################### PRINT #########################
        }

        foreach($update['eco'] as $v1 => $dados){
            echo '<br> >>>  Update ECOMMERCE  vendaID: <strong> '.$v1 . '</strong> ';

            $venda = $this->EcmVenda->get($v1);
            if($venda){
                $venda = $this->EcmVenda->patchEntity($venda, $dados);

                if($this->EcmVenda->save($venda)){
                    echo ' >>> VENDA ATUALIZADA COM SUCESSO! ';

                }else{
                    echo '>>>  FALHA AO ATUALIZAR VENDA! ';

                    if($venda->errors()){
                        foreach( $venda->errors() as $key => $errors){
                            if(is_array($errors)){
                                foreach($errors as $error){
                                    echo '<br> >>>  '.$key.':'.$error;
                                }
                            }else{
                                var_dump($errors);
                            }
                        }
                    }
                }

            }else{
                echo ' >>>  FALHA AO ATUALIZAR VENDA! ';
            }

            ob_flush();
            flush();
        }

        foreach($update['dbaqi'] as $proposta => $info){
            foreach($info as $codigo => $dados){
                echo '<br> >>>  Update TOP  proposta: <strong> '.$proposta . '</strong> codigo:'. $codigo;
                echo '<br>';
                
                $sql = 'UPDATE PedidoVencimentos SET ';

                foreach($dados as $k => $v){
                    $sql .=  $k .' = :'.$k.',';
                }
                $sql = substr( $sql , 0, -1);
                $sql .= ' WHERE IDPEDIDO = :IDPEDIDO AND CodigoDoRegistro = :CodigoDoRegistro';

                $dados['IDPEDIDO'] = $proposta;
                $dados['CodigoDoRegistro'] = $codigo;

                echo ' SQL: '.$sql;
                echo '<br>';
                var_dump($dados);

                $stmt =  $this->dbaqi->execute($sql, $dados);

                $code = $stmt->errorCode();
                $info = $stmt->errorInfo();

                echo '<br> CODE:';
                var_dump($code);
                echo ' INFO:';
                var_dump($info);

            }

            ob_flush();
            flush();
        }

        echo '<br> <strong> ### END - PROCESSO ### </strong> <br>';
        $data = new \DateTime();
        echo $data->format( "d/m/Y H:i:s" );
        ob_end_flush();

        die;
    }*/

}


