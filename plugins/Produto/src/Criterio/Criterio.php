<?php
namespace Produto\Criterio;

use Cake\ORM\TableRegistry;

/**
 * Amount completion criteria type
 * Criteria type constant, primarily for storing criteria type in the database.
 */
define('CRITERIO_TIPO_QUANTIDADE', 1);

/**
 * Vacancies completion criteria type
 * Criteria type constant, primarily for storing criteria type in the database.
 */
define('CRITERIO_TIPO_VAGAS',      2);

/**
 * Recurrent completion criteria type
 * Criteria type constant, primarily for storing criteria type in the database.
 */
define('CRITERIO_TIPO_RECORRENTE', 3);

/**
 * Schedule completion criteria type
 * Criteria type constant, primarily for storing criteria type in the database.
 */
define('CRITERIO_TIPO_AGENDAR',    4);

class Criterio
{
    private $tipos = array(
        CRITERIO_TIPO_QUANTIDADE => 'Quantidade',
        CRITERIO_TIPO_VAGAS      => 'Vagas',
        CRITERIO_TIPO_RECORRENTE => 'Recorrente',
        CRITERIO_TIPO_AGENDAR    => 'Agendar'
    );
    private $ausente = array(
        CRITERIO_TIPO_QUANTIDADE => true,
        CRITERIO_TIPO_VAGAS      => true,
        CRITERIO_TIPO_RECORRENTE => false,
        CRITERIO_TIPO_AGENDAR    => false
    );

    public function get_status($request, $carrinho, $criterio)
    {
        $this->EcmTipoProduto = TableRegistry::get('EcmTipoProduto');
        $ecmTipoProduto = $this->EcmTipoProduto->find('list', [
            'valueField' => 'id'
        ])->where(['p.id' => $request['produto']])
            ->join([
                'ptp' => [
                    'table' => 'ecm_produto_ecm_tipo_produto',
                    'type' => 'INNER',
                    'conditions' => 'ptp.ecm_tipo_produto_id = EcmTipoProduto.id',
                ],
                'p' => [
                    'table' => 'ecm_produto',
                    'type' => 'INNER',
                    'conditions' => 'p.id = ptp.ecm_produto_id',
                ]
            ])->toArray();

        $this->EcmProdutoCriterios = TableRegistry::get('EcmProdutoCriterios');
        $criterios = $this->EcmProdutoCriterios->find()->select([
            'criterio', 'quantidade'
        ])->where(['OR' => [
            'ecm_produto_id' => $request['produto'],
            'ecm_tipo_produto_id IN' => $ecmTipoProduto
        ]]);

        if(!is_null($criterio))
            $criterios->where(['criterio IN' => $criterio]);

        $criterios = $criterios->toArray();
        if(empty($criterios)){
            $retorno = ['sucesso' => false, 'mensagem' => 'Este produto não contem criterios de venda'];
            if(!is_null($criterio)) {
                if(is_array($criterio)) {
                    foreach($criterio as $cri){
                        if(!$this->ausente[$cri]) return $retorno;
                    }
                } else {
                    if(!$this->ausente[$criterio]) return $retorno;
                }
            }
            $retorno['sucesso'] = true;
            return $retorno;
        }else{
            foreach($criterios as $criterio){
                $class = 'Produto\Criterio\Criterio_' . $this->tipos[$criterio['criterio']];
                $class = new $class();

                $args = $class->get_args($criterios);
                if($args !== false){
                    $result = $class->review($request, $carrinho, $args);
                    if(!$result["sucesso"]){
                        return $result;
                    }
                } else {
                    return ['sucesso' => false, 'mensagem' => 'Este produto contem um criterio que requer parametros'];
                }
            }
        }
        return ['sucesso' => true, 'mensagem' => 'Esta requisicao atende todos os criterios'];
    }
}

interface CriterioInterface
{
    public function review($request, $carrinho, $args = array());
    public function get_args($args = array());
}