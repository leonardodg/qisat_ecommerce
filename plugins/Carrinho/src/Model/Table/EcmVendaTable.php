<?php
namespace Carrinho\Model\Table;

use App\Model\Table\Table;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Carrinho\Model\Entity\EcmVenda;

/**
 * EcmVenda Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmVendaStatus * @property \Cake\ORM\Association\BelongsTo $MdlUser * @property \Cake\ORM\Association\BelongsTo $EcmOperadoraPagamento * @property \Cake\ORM\Association\BelongsTo $EcmTipoPagamento * @property \Cake\ORM\Association\BelongsTo $EcmCarrinho * @property \Cake\ORM\Association\HasMany $EcmTransacao */
class EcmVendaTable extends Table
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

        $this->table('ecm_venda');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmVendaStatus', [
            'foreignKey' => 'ecm_venda_status_id',
            'joinType' => 'INNER',
            'className' => 'Carrinho.EcmVendaStatus'
        ]);
        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'className' => 'MdlUser'
        ]);
        $this->belongsTo('EcmOperadoraPagamento', [
            'foreignKey' => 'ecm_operadora_pagamento_id',
            'className' => 'FormaPagamento.EcmOperadoraPagamento'
        ]);
        $this->belongsTo('EcmTipoPagamento', [
            'foreignKey' => 'ecm_tipo_pagamento_id',
            'className' => 'FormaPagamento.EcmTipoPagamento'
        ]);
        $this->belongsTo('EcmCarrinho', [
            'foreignKey' => 'ecm_carrinho_id',
            'joinType' => 'INNER',
            'className' => 'Carrinho.EcmCarrinho'
        ]);
        $this->hasMany('EcmTransacao', [
            'foreignKey' => 'ecm_venda_id',
            'className' => 'Carrinho.EcmTransacao'
        ]);
        $this->hasMany('EcmRecorrencia', [
            'foreignKey' => 'ecm_venda_id',
            'className' => 'Carrinho.EcmRecorrencia'
        ]);
        $this->hasMany('EcmVendaBoleto', [
            'foreignKey' => 'ecm_venda_id',
            'joinType' => 'LEFT',
            'className' => 'Carrinho.EcmVendaBoleto'
        ]);
        $this->hasMany('EcmCursoPresencialEmailConfirmacao', [
            'foreignKey' => 'ecm_venda_id',
            'joinType' => 'INNER',
            'className' => 'Carrinho.EcmCursoPresencialEmailConfirmacao'
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
            ->dateTime('data')            ->requirePresence('data', 'create')            ->notEmpty('data');
        $validator
            ->decimal('valor_parcelas')            ->allowEmpty('valor_parcelas');
        $validator
            ->allowEmpty('proposta');
        $validator
            ->integer('numero_parcelas')            ->requirePresence('numero_parcelas', 'create')            ->notEmpty('numero_parcelas');
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
        $rules->add($rules->existsIn(['ecm_venda_status_id'], 'EcmVendaStatus'));
        $rules->add($rules->existsIn(['mdl_user_id'], 'MdlUser'));
        $rules->add($rules->existsIn(['ecm_operadora_pagamento_id'], 'EcmOperadoraPagamento'));
        $rules->add($rules->existsIn(['ecm_tipo_pagamento_id'], 'EcmTipoPagamento'));
        $rules->add($rules->existsIn(['ecm_carrinho_id'], 'EcmCarrinho'));
        return $rules;
    }

    public function beforeSave(Event $event){
        $entity = $event->data['entity'];

        if(is_null($entity->id)){
            $entity->set('data', new \DateTime());
        }
    }
}
