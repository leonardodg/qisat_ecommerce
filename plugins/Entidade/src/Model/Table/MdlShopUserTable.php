<?php
namespace Entidade\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Entidade\Model\Entity\MdlShopUser;

/**
 * MdlShopUser Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmAlternativeHost * @property \Cake\ORM\Association\BelongsTo $Prospeccaos */
class MdlShopUserTable extends Table
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

        $this->table('mdl_shop_user');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
            'className' => 'Entidade.EcmAlternativeHost'
        ]);
        $this->hasMany('MdlUserEcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
            'joinType' => 'LEFT',
            'className' => 'Entidade.MdlUserEcmAlternativeHost'
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
            ->notEmpty('cpf_cnpj');
        $validator
            ->notEmpty('crea');
        $validator
            ->requirePresence('nome', 'create')            ->notEmpty('nome');
        $validator
            ->integer('adimplente')            ->requirePresence('adimplente', 'create')            ->notEmpty('adimplente');
        $validator
            ->notEmpty('ecm_alternative_host_id');
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
        $rules->add($rules->existsIn(['ecm_alternative_host_id'], 'EcmAlternativeHost'));
        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entityInterface, \ArrayObject $options){
        $entity = $event->data['entity'];

        $cpf = preg_replace("/[^0-9]/", "", $entity->cpf_cnpj);
        $entity->set('cpf_cnpj', $cpf);
    }
}
