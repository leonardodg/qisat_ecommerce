<?php
namespace Convenio\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Convenio\Model\Entity\EcmConvenioInteresse;

/**
 * EcmConvenioInteresse Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmConvenio */
class EcmConvenioInteresseTable extends Table
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

        $this->table('ecm_convenio_interesse');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmConvenio', [
            'foreignKey' => 'ecm_convenio_id',
            'joinType' => 'INNER',
            'className' => 'Convenio.EcmConvenio'
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
            ->requirePresence('nome', 'create')            ->notEmpty('nome');
        $validator
            ->requirePresence('telefone', 'create')            ->notEmpty('telefone');
        $validator
            ->email('email')            ->requirePresence('email', 'create')            ->notEmpty('email');
        $validator
            ->allowEmpty('chave_altoqi');
        $validator
            ->dateTime('data_registro')            ->requirePresence('data_registro', 'create')            ->notEmpty('data_registro');
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
        //$rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['ecm_convenio_id'], 'EcmConvenio'));
        return $rules;
    }
}
