<?php
namespace Produto\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Produto\Model\Entity\EcmProdutoEcmTipoProdutoEcmAlternativeHost;

/**
 * EcmProdutoEcmTipoProdutoEcmAlternativeHost Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmProdutoEcmTipoProduto * @property \Cake\ORM\Association\BelongsTo $EcmAlternativeHost */
class EcmProdutoEcmTipoProdutoEcmAlternativeHostTable extends Table
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

        $this->table('ecm_produto_ecm_tipo_produto_ecm_alternative_host');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmAlternativeHost'
        ]);
        $this->belongsTo('EcmProdutoEcmTipoProduto', [
            'foreignKey' => 'ecm_produto_ecm_tipo_produto_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmProdutoEcmTipoProduto'
        ]);
        $this->EcmProdutoEcmTipoProduto->belongsTo('EcmTipoProduto', [
            'foreignKey' => 'ecm_tipo_produto_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmTipoProduto'
        ]);
        $this->EcmProdutoEcmTipoProduto->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmProduto'
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
        /*$validator
            ->requirePresence('ordem', 'create')            ->notEmpty('ordem');*/
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['ecm_produto_tipo_produto_id'], 'EcmProdutoEcmTipoProduto'));
        $rules->add($rules->existsIn(['ecm_alternative_host_id'], 'EcmAlternativeHost'));
        return $rules;
    }
}
