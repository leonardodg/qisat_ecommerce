<?php
namespace Instrutor\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Instrutor\Model\Entity\EcmInstrutorArtigo;

/**
 * EcmInstrutorArtigo Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmInstrutor */
class EcmInstrutorArtigoTable extends Table
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

        $this->table('ecm_instrutor_artigo');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmInstrutor', [
            'foreignKey' => 'ecm_instrutor_id',
            'joinType' => 'INNER',
            'className' => 'Instrutor.EcmInstrutor'
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
            ->integer('id')            ->allowEmpty('id', 'create');
        $validator
            ->requirePresence('titulo', 'create')            ->notEmpty('titulo');
        $validator
            ->requirePresence('link', 'create')            ->notEmpty('link');
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
        $rules->add($rules->existsIn(['ecm_instrutor_id'], 'EcmInstrutor'));
        return $rules;
    }
}
