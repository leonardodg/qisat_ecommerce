<?php
namespace Repasse\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Repasse\Model\Entity\EcmRepasseOrigem;

/**
 * EcmRepasseOrigem Model
 *
 * @property \Cake\ORM\Association\HasMany $EcmRepasse */
class EcmRepasseOrigemTable extends Table
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

        $this->table('ecm_repasse_origem');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('EcmRepasse', [
            'foreignKey' => 'ecm_repasse_origem_id',
            'className' => 'Repasse.EcmRepasse'
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
            ->requirePresence('origem', 'create')            ->notEmpty('origem');
        return $validator;
    }
}
