<?php
namespace Vendas\Model\Table;

use App\Model\Table\Table;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Vendas\Model\Entity\EcmVenda;

/**
 * EcmVenda Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmVendaStatus * @property \Cake\ORM\Association\BelongsTo $MdlUser * @property \Cake\ORM\Association\BelongsTo $EcmOperadoraPagamento * @property \Cake\ORM\Association\BelongsTo $EcmTipoPagamento * @property \Cake\ORM\Association\BelongsTo $EcmCarrinho * @property \Cake\ORM\Association\HasMany $EcmCursoPresencialEmailConfirmacao * @property \Cake\ORM\Association\HasMany $EcmTransacao * @property \Cake\ORM\Association\HasMany $EcmVendaBoleto */
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
            'className' => 'Vendas.EcmVendaStatus'
        ]);
        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'INNER',
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
        $this->hasMany('EcmCursoPresencialEmailConfirmacao', [
            'foreignKey' => 'ecm_venda_id',
            'className' => 'Vendas.EcmCursoPresencialEmailConfirmacao'
        ]);
        $this->hasMany('EcmTransacao', [
            'foreignKey' => 'ecm_venda_id',
            'className' => 'Carrinho.EcmTransacao'
        ]);
        $this->hasMany('EcmVendaBoleto', [
            'foreignKey' => 'ecm_venda_id',
            'className' => 'Vendas.EcmVendaBoleto'
        ]);
        $this->hasMany('EcmRecorrencia', [
            'foreignKey' => 'ecm_venda_id',
            'className' => 'Carrinho.EcmRecorrencia'
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
            ->allowEmpty('pedido');
        $validator
            ->boolean('pedido_status')            ->requirePresence('pedido_status', 'create')            ->notEmpty('pedido_status');
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
        $rules->add($rules->existsIn(['ecm_carrinho_id'], 'EcmCarrinho'));
        $rules->add($rules->existsIn(['ecm_venda_status_id'], 'EcmVendaStatus'));
        $rules->add($rules->existsIn(['mdl_user_id'], 'MdlUser'));
        $rules->add($rules->existsIn(['ecm_operadora_pagamento_id'], 'EcmOperadoraPagamento'));
        $rules->add($rules->existsIn(['ecm_tipo_pagamento_id'], 'EcmTipoPagamento'));
        return $rules;
    }

    /**
     * AlterarStatusVenda method
     *
     * @param int $id Ecm Venda id.
     * @param int|string $status Ecm Venda status.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function alterarStatusVenda($id, $status)
    {
        $ecmVenda = $this->get($id);
        if(!intval($status)){
            $status = $this->EcmVendaStatus->find('all', ['conditions' => ['status' => $status]])->first()->id;
        }
        $ecmVenda['ecm_venda_status_id'] = $status;
        $ecmVendaStatus = $this->EcmVendaStatus->get($ecmVenda['ecm_venda_status_id']);
        $ecmCarrinho = $this->EcmCarrinho->get($ecmVenda['ecm_carrinho_id']);
        switch($ecmVendaStatus->status){
            case "Andamento":
                $ecmCarrinho['status'] = 'Em Aberto';
                break;
            case "Finalizada":
                $ecmCarrinho['status'] = 'Finalizado';
                break;
            default:
                $ecmCarrinho['status'] = 'Cancelado';
                break;
        }
        if ($this->save($ecmVenda) && $this->EcmCarrinho->save($ecmCarrinho)) {
            return true;
        }
        return false;
    }

    public function beforeSave(Event $event){
        $entity = $event->data['entity'];

        if(is_null($entity->id)){
            $entity->set('data', new \DateTime());
        }
    }
}
