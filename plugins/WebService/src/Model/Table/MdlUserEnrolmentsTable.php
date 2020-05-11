<?php
namespace WebService\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use WebService\Model\Entity\MdlUserEnrolment;

/**
 * MdlUserEnrolments Model
 *
 */
class MdlUserEnrolmentsTable extends Table
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

        $this->table('mdl_user_enrolments');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('MdlEnrol', [
            'foreignKey' => 'enrolid',
            'joinType' => 'INNER',
            'className' => 'MdlEnrol'
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
            ->requirePresence('status', 'create')            ->notEmpty('status');
        $validator
            ->requirePresence('enrolid', 'create')            ->notEmpty('enrolid');
        $validator
            ->requirePresence('userid', 'create')            ->notEmpty('userid');
        $validator
            ->requirePresence('timestart', 'create')            ->notEmpty('timestart');
        $validator
            ->requirePresence('timeend', 'create')            ->notEmpty('timeend');
        $validator
            ->requirePresence('modifierid', 'create')            ->notEmpty('modifierid');
        $validator
            ->requirePresence('timecreated', 'create')            ->notEmpty('timecreated');
        $validator
            ->requirePresence('timemodified', 'create')            ->notEmpty('timemodified');
        return $validator;
    }
}
