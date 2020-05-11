<?php
namespace App\Model\Table;

use App\Model\Entity\EcmLogAcao;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * EcmLogAcao Model
 *
 * @property \Cake\ORM\Association\BelongsTo $MdlUser */
class EcmLogAcaoTable extends \Cake\ORM\Table
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

        $this->table('ecm_log_acao');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'INNER'
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
            ->requirePresence('tabela', 'create')            ->notEmpty('tabela');
        $validator
            ->requirePresence('acao', 'create')            ->notEmpty('acao');
        $validator
            ->requirePresence('chave', 'create')            ->notEmpty('chave');
        $validator
            ->dateTime('data')            ->requirePresence('data', 'create')            ->notEmpty('data');
        $validator
            ->requirePresence('ip', 'create')            ->notEmpty('ip');
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
        $rules->add($rules->existsIn(['mdl_user_id'], 'MdlUser'));
        return $rules;
    }
}
