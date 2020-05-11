<?php
namespace Promocao\Model\Table;

use App\Model\Entity\MdlUser;
use App\Model\Table\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Entidade\Model\Entity\EcmAlternativeHost;
use Produto\Model\Entity\EcmProduto;

/**
 * EcmPromocao Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $EcmAlternativeHost * @property \Cake\ORM\Association\BelongsToMany $EcmProduto */
class EcmPromocaoTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('ecm_promocao');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsToMany('EcmAlternativeHost', [
            'foreignKey' => 'ecm_promocao_id',
            'targetForeignKey' => 'ecm_alternative_host_id',
            'joinTable' => 'ecm_promocao_ecm_alternative_host',
            'className' => 'Entidade.EcmAlternativeHost',
            'joinType' => 'INNER'
        ]);
        $this->belongsToMany('EcmProduto', [
            'foreignKey' => 'ecm_promocao_id',
            'targetForeignKey' => 'ecm_produto_id',
            'joinTable' => 'ecm_promocao_ecm_produto',
            'className' => 'Promocao.EcmProduto'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');
        $validator
            ->date('datainicio',['dmy'])->requirePresence('datainicio', 'create')            ->notEmpty('datainicio');
        $validator
            ->date('datainicio',['dmy'])->requirePresence('datafim', 'create')            ->notEmpty('datafim');
        $validator
            ->decimal('descontovalor')            ->allowEmpty('descontovalor');
        $validator
            ->decimal('descontoporcentagem')            ->allowEmpty('descontoporcentagem');
        $validator
            ->allowEmpty('descricao');
        $validator
            ->requirePresence('habilitado', 'create')            ->notEmpty('habilitado');
        $validator
            ->requirePresence('arredondamento', 'create')            ->notEmpty('arredondamento');
        return $validator;
    }

    public function beforeSave($options){

        $promocao = $options->data['entity'];

        $promocao->descontovalor =  str_replace(',','.',str_replace('.','',$promocao->descontovalor));
        $promocao->descontoporcentagem =  str_replace(',','.',str_replace('.','',$promocao->descontoporcentagem));
    }

    /**
     * Função responsável por buscar as promoções válidas de um produto
     *
     * @deprecated
     *
     * @param EcmProduto $produto
     *
     * @return array
     *
     * */
    public function buscaPromocoesAtivasProduto(EcmProduto $produto, EcmAlternativeHost $alternativeHost = null){
        $dataAtual = new \DateTime();

        $listaPromocoes =  $this->find('all')
            ->innerJoin('ecm_promocao_ecm_produto', [
                'ecm_promocao_ecm_produto.ecm_promocao_id = EcmPromocao.id'
            ])
            ->innerJoin('ecm_produto', ['ecm_produto.id = ecm_promocao_ecm_produto.ecm_produto_id'])
            ->where([
                'ecm_produto.id' => $produto->id,
                'EcmPromocao.habilitado' => 'true',
                'EcmPromocao.datainicio <=' => $dataAtual->format('Y-m-d'),
                'EcmPromocao.datafim >=' => $dataAtual->format('Y-m-d')
            ]);

        if(!is_null($alternativeHost)){
            $listaPromocoes->innerJoin('ecm_promocao_ecm_alternative_host', [
                'EcmPromocao.id = ecm_promocao_ecm_alternative_host.ecm_promocao_id',
                'ecm_promocao_ecm_alternative_host.ecm_alternative_host_id' => $alternativeHost->id
            ]);
        }

        return $listaPromocoes->toList();
    }

    /*
     * Função responsável por buscar as promoções válidas de um produto
     *
     * @param EcmProduto $produto
     *
     * @return array
     *
     * */
    public function buscaPromocoesAtivasUsuario(EcmProduto $produto, $mdlUser = null){
        $dataAtual = new \DateTime();

        $listaPromocoes = $this->find('all')->distinct(['EcmPromocao.id'])
            ->innerJoin('ecm_promocao_ecm_produto', [
                'ecm_promocao_ecm_produto.ecm_promocao_id = EcmPromocao.id'
            ])
            ->innerJoin('ecm_produto', ['ecm_produto.id = ecm_promocao_ecm_produto.ecm_produto_id'])
            ->innerJoin('ecm_promocao_ecm_alternative_host', [
                'EcmPromocao.id = ecm_promocao_ecm_alternative_host.ecm_promocao_id'
            ])
            ->where([
                'ecm_produto.id' => $produto->id,
                'EcmPromocao.habilitado' => 'true',
                'EcmPromocao.datainicio <=' => $dataAtual->format('Y-m-d'),
                'EcmPromocao.datafim >=' => $dataAtual->format('Y-m-d')
            ]);

        if(is_null($mdlUser)) {
            $listaPromocoes->where(['ecm_promocao_ecm_alternative_host.ecm_alternative_host_id = 1']);
        } else {
            $this->EcmConfig = TableRegistry::get('EcmConfig');
            $ecmConfig = $this->EcmConfig->find('list', ['keyField' => 'nome', 'valueField' => 'valor'])
                ->where(['nome LIKE "vender_crea_%"'])->toArray();

            $where = ['mdl_user_ecm_alternative_host.mdl_user_id' => $mdlUser];
            if (!isset($ecmConfig['vender_crea_inadimplente']) || $ecmConfig['vender_crea_inadimplente'] == 0)
                $where['mdl_user_ecm_alternative_host.adimplente'] = 1;
            if (!isset($ecmConfig['vender_crea_nao_confirmado']) || $ecmConfig['vender_crea_nao_confirmado'] == 0)
                $where['mdl_user_ecm_alternative_host.confirmado'] = 1;
            $listaPromocoes->leftJoin('mdl_user_ecm_alternative_host',
                ['mdl_user_ecm_alternative_host.ecm_alternative_host_id = ecm_promocao_ecm_alternative_host.ecm_alternative_host_id'])
                ->where(['OR' => [$where,['ecm_promocao_ecm_alternative_host.ecm_alternative_host_id = 1']]]);
        }

        return $listaPromocoes->toList();
    }
}
