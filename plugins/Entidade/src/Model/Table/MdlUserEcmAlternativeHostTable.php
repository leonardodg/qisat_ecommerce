<?php
namespace Entidade\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Entidade\Model\Entity\MdlUserEcmAlternativeHost;

/**
 * MdlUserEcmAlternativeHost Model
 *
 * @property \Cake\ORM\Association\BelongsTo $MdlUser * @property \Cake\ORM\Association\BelongsTo $EcmAlternativeHost */
class MdlUserEcmAlternativeHostTable extends Table
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

        $this->table('mdl_user_ecm_alternative_host');
        $this->displayField('mdl_user_id');
        $this->primaryKey(['mdl_user_id', 'ecm_alternative_host_id']);

        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'INNER',
            'className' => 'Entidade.MdlUser'
        ]);
        $this->belongsTo('EcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
            'joinType' => 'INNER',
            'className' => 'Entidade.EcmAlternativeHost'
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
            ->allowEmpty('numero');
        $validator
            ->integer('adimplente')            ->allowEmpty('adimplente');
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
        $rules->add($rules->existsIn(['ecm_alternative_host_id'], 'EcmAlternativeHost'));
        return $rules;
    }
}
