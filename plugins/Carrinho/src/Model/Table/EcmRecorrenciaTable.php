<?php
namespace Carrinho\Model\Table;

use App\Model\Table\Table;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Carrinho\Model\Entity\EcmRecorrencia;

/**
 * EcmRecorrencia Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmRecorrenciaStatus * @property \Cake\ORM\Association\BelongsTo $MdlUser * @property \Cake\ORM\Association\BelongsTo $EcmTipoPagamento * @property \Cake\ORM\Association\BelongsTo $EcmOperadoraPagamento * @property \Cake\ORM\Association\BelongsTo $EcmVenda */
class EcmRecorrenciaTable extends Table
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

        $this->table('ecm_recorrencia');
        $this->displayField('id');
        $this->primaryKey('id');

        
        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'INNER',
            'className' => 'Carrinho.MdlUser'
        ]);
        $this->belongsTo('EcmTipoPagamento', [
            'foreignKey' => 'ecm_tipo_pagamento_id',
            'joinType' => 'INNER',
            'className' => 'FormaPagamento.EcmTipoPagamento'
        ]);
        $this->belongsTo('EcmOperadoraPagamento', [
            'foreignKey' => 'ecm_operadora_pagamento_id',
            'joinType' => 'INNER',
            'className' => 'FormaPagamento.EcmOperadoraPagamento'
        ]);
        $this->belongsTo('EcmVenda', [
            'foreignKey' => 'ecm_venda_id',
            'joinType' => 'INNER',
            'className' => 'Carrinho.EcmVenda'
        ]);

        $this->hasMany('EcmTransacao', [
            'foreignKey' => 'ecm_recorrencia_id',
            'className' => 'Carrinho.EcmTransacao'
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
            ->allowEmpty('id_integracao');
        $validator
            ->requirePresence('capturar', 'create')            ->notEmpty('capturar');
        $validator
            ->decimal('valor')            ->requirePresence('valor', 'create')            ->notEmpty('valor');
        $validator
            ->allowEmpty('descricao');
        $validator
            ->dateTime('data_envio')            ->requirePresence('data_envio', 'create')            ->notEmpty('data_envio');
        $validator
            ->dateTime('data_retorno')            ->allowEmpty('data_retorno');
        $validator
            ->allowEmpty('ip');
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
        $rules->add($rules->existsIn(['ecm_tipo_pagamento_id'], 'EcmTipoPagamento'));
        $rules->add($rules->existsIn(['ecm_operadora_pagamento_id'], 'EcmOperadoraPagamento'));
        $rules->add($rules->existsIn(['ecm_venda_id'], 'EcmVenda'));
        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options){
        $entity = $event->data['entity'];

        if(is_null($entity->id)){
            $entity->set('data_envio', new \DateTime());
        }
    }
}
