<?php
namespace DuvidasFrequentes\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use DuvidasFrequentes\Model\Entity\EcmDuvidasFrequente;

/**
 * EcmDuvidasFrequentes Model
 *
 */
class EcmDuvidasFrequentesTable extends Table
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

        $this->table('ecm_duvidas_frequentes');
        $this->displayField('id');
        $this->primaryKey('id');
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
            ->requirePresence('titulo', 'create')            ->notEmpty('titulo');
        $validator
            ->requirePresence('url', 'create')            ->notEmpty('url');
        $validator
            ->integer('ordem')            ->requirePresence('ordem', 'create')            ->notEmpty('ordem');
        $validator
            ->dateTime('timemodified')            ->requirePresence('timemodified', 'create')            ->notEmpty('timemodified');
        return $validator;
    }
}
