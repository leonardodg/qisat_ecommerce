<?php
namespace Convenio\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Convenio\Model\Entity\EcmConvenioContrato;

/**
 * EcmConvenioContrato Model
 *
 * @property \Cake\ORM\Association\HasMany $EcmConvenio */
class EcmConvenioContratoTable extends Table
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

        $this->table('ecm_convenio_contrato');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('EcmConvenio', [
            'foreignKey' => 'ecm_convenio_contrato_id',
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
            ->integer('id')            ->allowEmpty('id', 'create');
        $validator
            ->dateTime('data_inicio_convenio',['dmy'])            ->requirePresence('data_inicio_convenio', 'create')            ->notEmpty('data_inicio_convenio');
        $validator
            ->dateTime('data_fim_convenio',['dmy'])            ->requirePresence('data_fim_convenio', 'create')            ->notEmpty('data_fim_convenio');
        $validator
            ->requirePresence('contrato_ativo', 'create')            ->notEmpty('contrato_ativo');
        $validator
            ->requirePresence('contrato_assinado', 'create')            ->notEmpty('contrato_assinado');
        return $validator;
    }

    public function beforeSave(Event $event, EntityInterface $entityInterface, \ArrayObject $options){
        $entity = $event->data['entity'];

        if(is_null($entity->id)){
            $entity->set('data_registro', new \DateTime());
        }

        return true;
    }
}
