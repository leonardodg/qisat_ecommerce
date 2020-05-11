<?php
namespace Vendas\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Vendas\Model\Entity\EcmVendaBoleto;

/**
 * EcmVendaBoleto Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmVenda */
class EcmVendaBoletoTable extends Table
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

        $this->table('ecm_venda_boleto');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmVenda', [
            'foreignKey' => 'ecm_venda_id',
            'joinType' => 'INNER',
            'className' => 'Vendas.EcmVenda'
        ]);
        $this->EcmVenda->hasOne('MdlUser', [
            'foreignKey' => 'id',
            'bindingKey' => 'mdl_user_id',
            'joinType' => 'INNER',
            'className' => 'MdlUser'
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
            ->integer('parcela')            ->requirePresence('parcela', 'create')            ->notEmpty('parcela');
        $validator
            ->requirePresence('status', 'create')            ->notEmpty('status');
        $validator
            ->dateTime('data')            ->allowEmpty('data');
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
        $rules->add($rules->existsIn(['ecm_venda_id'], 'EcmVenda'));
        return $rules;
    }
}
