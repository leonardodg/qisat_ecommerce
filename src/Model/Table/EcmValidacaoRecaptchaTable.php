<?php
namespace WebService\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use WebService\Model\Entity\EcmValidacaoRecaptcha;

/**
 * EcmValidacaoRecaptcha Model
 *
 */
class EcmValidacaoRecaptchaTable extends Table
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

        $this->table('ecm_validacao_recaptcha');
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
            ->integer('id')            ->allowEmpty('id', 'create');
        $validator
            ->requirePresence('action', 'create')            ->notEmpty('action');
        $validator
            ->requirePresence('controller', 'create')            ->notEmpty('controller');
        $validator
            ->requirePresence('plugin', 'create')            ->notEmpty('plugin');
        return $validator;
    }
}
