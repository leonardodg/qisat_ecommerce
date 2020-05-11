<?php
namespace WebService\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use WebService\Model\Entity\MdlCertificate;

/**
 * MdlCertificate Model
 *
 */
class MdlCertificateTable extends Table
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

        $this->table('mdl_certificate');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsTo('MdlCertificateIssues', [
            'foreignKey' => 'id',
            'bindingKey' => 'certificateid',
            'joinType' => 'INNER',
            'className' => 'MdlCertificateIssues'
        ]);
        $this->belongsTo('MdlCourse', [
            'foreignKey' => 'course',
            'bindingKey' => 'id',
            'joinType' => 'INNER',
            'className' => 'MdlCourse'
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
            ->requirePresence('course', 'create')            ->notEmpty('course');
        $validator
            ->requirePresence('name', 'create')            ->notEmpty('name');
        $validator
            ->allowEmpty('intro');
        $validator
            ->integer('introformat')            ->requirePresence('introformat', 'create')            ->notEmpty('introformat');
        $validator
            ->boolean('emailteachers')            ->requirePresence('emailteachers', 'create')            ->notEmpty('emailteachers');
        $validator
            ->allowEmpty('emailothers');
        $validator
            ->boolean('savecert')            ->requirePresence('savecert', 'create')            ->notEmpty('savecert');
        $validator
            ->boolean('reportcert')            ->requirePresence('reportcert', 'create')            ->notEmpty('reportcert');
        $validator
            ->integer('delivery')            ->requirePresence('delivery', 'create')            ->notEmpty('delivery');
        $validator
            ->requirePresence('requiredtime', 'create')            ->notEmpty('requiredtime');
        $validator
            ->requirePresence('certificatetype', 'create')            ->notEmpty('certificatetype');
        $validator
            ->requirePresence('orientation', 'create')            ->notEmpty('orientation');
        $validator
            ->requirePresence('borderstyle', 'create')            ->notEmpty('borderstyle');
        $validator
            ->requirePresence('bordercolor', 'create')            ->notEmpty('bordercolor');
        $validator
            ->requirePresence('printwmark', 'create')            ->notEmpty('printwmark');
        $validator
            ->requirePresence('printdate', 'create')            ->notEmpty('printdate');
        $validator
            ->requirePresence('datefmt', 'create')            ->notEmpty('datefmt');
        $validator
            ->boolean('printnumber')            ->requirePresence('printnumber', 'create')            ->notEmpty('printnumber');
        $validator
            ->requirePresence('printgrade', 'create')            ->notEmpty('printgrade');
        $validator
            ->requirePresence('gradefmt', 'create')            ->notEmpty('gradefmt');
        $validator
            ->requirePresence('printoutcome', 'create')            ->notEmpty('printoutcome');
        $validator
            ->allowEmpty('printhours');
        $validator
            ->requirePresence('printteacher', 'create')            ->notEmpty('printteacher');
        $validator
            ->allowEmpty('customtext');
        $validator
            ->requirePresence('printsignature', 'create')            ->notEmpty('printsignature');
        $validator
            ->requirePresence('printseal', 'create')            ->notEmpty('printseal');
        $validator
            ->requirePresence('timecreated', 'create')            ->notEmpty('timecreated');
        $validator
            ->requirePresence('timemodified', 'create')            ->notEmpty('timemodified');
        return $validator;
    }
}
