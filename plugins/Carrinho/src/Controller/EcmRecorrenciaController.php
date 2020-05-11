<?php
namespace Carrinho\Controller;

use Carrinho\Controller\AppController;

/**
 * EcmRecorrencia Controller
 *
 * @property \Carrinho\Model\Table\EcmRecorrenciaTable $EcmRecorrencia */
class EcmRecorrenciaController extends AppController
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
        $tabelaRecorrencias = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $tabelaAtivas   = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $tabelaDesativas = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $tabelaTotal = [0,0,0];

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

        if(!empty($this->request->data)){
            $id = $this->request->data('id');
            $status = $this->request->data('status');
            $mes = $this->request->data('mes');
            $pedido = $this->request->data('pedido');
            $venda = $this->request->data('venda');
            $proposta = $this->request->data('proposta');

            if(!empty($id) || !empty($pedido) || !empty($proposta) || !empty($venda)){
                if(strlen(trim($id)) > 0){
                    if(strrpos($id, ',') >= 0)
                        $condition['OR']['EcmRecorrencia.id IN'] = explode(',', $id);
                    else
                        $condition['OR']['EcmRecorrencia.id'] = $id;
                }
                if(strlen(trim($pedido)) > 0)
                     $condition['OR']['EcmVenda.pedido'] = $pedido;
                if(strlen(trim($proposta)) > 0)
                     $condition['OR']['EcmVenda.proposta'] = $proposta;
                if(strlen(trim($venda)) > 0)
                    $condition['OR']['EcmVenda.id'] = $venda;
            }else{
                if(strlen(trim($status)) > 0)
                $condition['EcmRecorrencia.status'] = $status;
            
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
                $condition['data_envio >='] = $dataInicio->format('Y-m-d H:i:s');
                $condition['data_envio <='] = $dataFim->format('Y-m-d H:i:s');
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
            $condition['data_envio >='] = $dataInicio->format('Y-m-d H:i:s');
            $condition['data_envio <='] = $dataFim->format('Y-m-d H:i:s');
        }


        $ecmRecorrencia = $this->EcmRecorrencia->find()
                                    ->contain(['MdlUser', 'EcmTipoPagamento' => ['EcmFormaPagamento'], 
                                                    'EcmOperadoraPagamento', 'EcmVenda', 'EcmTransacao'])
                                    ->where($condition)
                                    ->order(['EcmRecorrencia.id' => 'DESC']);

        foreach ($ecmRecorrencia as $rec) {
            if($rec->data_envio){
                $dia = $rec->data_envio->format('j');
                $tabelaRecorrencias[$dia-1]++;
                $tabelaTotal[0]++;

                if($rec->status){
                    $tabelaAtivas[$dia-1]++;
                    $tabelaTotal[1]++;
                }else{
                    $tabelaDesativas[$dia-1]++;
                    $tabelaTotal[2]++;
                }
            }
        }

        $this->set(compact('ecmRecorrencia','list_mes', 'datanow', 'tabelaTotal','tabelaRecorrencias', 'tabelaAtivas', 'tabelaDesativas' ));
        $this->set('_serialize', ['ecmRecorrencia','list_mes','datanow','tabelaTotal', 'tabelaRecorrencias', 'tabelaAtivas', 'tabelaDesativas']);
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
        $ecmRecorrencia = $this->EcmRecorrencia->get($id, ['contain' => ['EcmTransacao', 'MdlUser', 'EcmTipoPagamento', 'EcmOperadoraPagamento', 'EcmVenda']]);

        $this->set('ecmRecorrencia', $ecmRecorrencia);
        $this->set('_serialize', ['ecmRecorrencia']);

        if (strpos($this->request->url, 'transacao-view') !== false)
            $this->render("transacao_view");
    }
}
