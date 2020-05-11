<?php
namespace App\Model\Table;

use App\Model\Entity\EcmLogContrato;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * EcmLogContrato Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmVenda * @property \Cake\ORM\Association\BelongsTo $MdlUserEnrolments */
class EcmLogContratoTable extends \Cake\ORM\Table
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

        $this->table('ecm_log_contrato');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmVenda', [
            'foreignKey' => 'ecm_venda_id'
        ]);
        $this->belongsTo('MdlUserEnrolments', [
            'foreignKey' => 'mdl_user_enrolments_id'
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
            ->requirePresence('timecreated', 'create')            ->notEmpty('timecreated');
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
        $rules->add($rules->existsIn(['mdl_user_enrolments_id'], 'MdlUserEnrolments'));
        return $rules;
    }
}
