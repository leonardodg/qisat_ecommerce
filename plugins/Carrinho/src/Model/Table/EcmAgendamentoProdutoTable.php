<?php
namespace Carrinho\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Carrinho\Model\Entity\EcmAgendamentoProduto;

/**
 * EcmAgendamentoProduto Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmCarrinhoItem */
class EcmAgendamentoProdutoTable extends Table
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

        $this->table('ecm_agendamento_produto');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmCarrinhoItem', [
            'foreignKey' => 'ecm_carrinho_item_id',
            'joinType' => 'INNER',
            'className' => 'Carrinho.EcmCarrinhoItem'
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
            ->dateTime('datainicio')            ->requirePresence('datainicio', 'create')            ->notEmpty('datainicio');
        $validator
            ->requirePresence('duracao', 'create')            ->notEmpty('duracao');
        $validator
            ->allowEmpty('pedido');
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
        $rules->add($rules->existsIn(['ecm_carrinho_item_id'], 'EcmCarrinhoItem'));
        return $rules;
    }
}
