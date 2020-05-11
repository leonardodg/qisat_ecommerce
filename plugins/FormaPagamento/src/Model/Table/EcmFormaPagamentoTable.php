<?php
namespace FormaPagamento\Model\Table;

use App\Model\Table\Table;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * EcmFormaPagamento Model
 *
 * @property \Cake\ORM\Association\HasMany $EcmOperadoraPagamento * @property \Cake\ORM\Association\HasMany $EcmTipoPagamento */
class EcmFormaPagamentoTable extends Table
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

        $this->table('ecm_forma_pagamento');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('EcmOperadoraPagamento', [
            'foreignKey' => 'ecm_forma_pagamento_id',
            'className' => 'FormaPagamento.EcmOperadoraPagamento'
        ]);
        $this->hasMany('EcmTipoPagamento', [
            'foreignKey' => 'ecm_forma_pagamento_id',
            'className' => 'FormaPagamento.EcmTipoPagamento'
        ]);
        $this->belongsToMany('EcmTipoProduto', [
            'foreignKey' => 'ecm_forma_pagamento_id',
            'targetForeignKey' => 'ecm_tipo_produto_id',
            'joinTable' => 'ecm_forma_pagamento_ecm_tipo_produto'
        ]);
        $this->belongsTo('EcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
            'className' => 'Entidade.EcmAlternativeHost'
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
            ->requirePresence('nome', 'create')            ->notEmpty('nome')            ->add('nome', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);
        $validator
            ->allowEmpty('dataname');
        $validator
            ->allowEmpty('descricao');
        $validator
            ->requirePresence('habilitado', 'create')            ->notEmpty('habilitado');
        $validator
            ->integer('parcelas')            ->requirePresence('parcelas', 'create')            ->notEmpty('parcelas');
        $validator
            ->requirePresence('controller', 'create')            ->notEmpty('controller');
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
        $rules->add($rules->isUnique(['nome']));
        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options){
        $formaPagamento = $event->data['entity'];

        if($formaPagamento['tipo'] != 'online' && $formaPagamento['habilitado'] === 'true'){
            $this->updateAll(['habilitado' => 'false'], ['habilitado' => 'true', 'tipo' => $formaPagamento['tipo']]);
        }
    }

    /**
     * Função que retorna uma lista de tipos de produto que tem relacionamento com o tipo de forma de pagamento
     * informado no parâmetro
     *
     * @param string $tipoFormaPagamento
     *
     * @return array com os objetos tipo de produto
     * */
    public function listarTipoProdutoPorTipoFormaPagamento($tipoFormaPagamento){
        $ecmTipoProduto = TableRegistry::get('EcmTipoProduto');

        return $ecmTipoProduto->find()
            ->innerJoin('ecm_forma_pagamento_ecm_tipo_produto', [
                'ecm_forma_pagamento_ecm_tipo_produto.ecm_tipo_produto_id = EcmTipoProduto.id'
            ])
            ->innerJoin('ecm_forma_pagamento', [
                'ecm_forma_pagamento.id = ecm_forma_pagamento_ecm_tipo_produto.ecm_forma_pagamento_id'
            ])
            ->where([
                'ecm_forma_pagamento.tipo' => $tipoFormaPagamento
            ])->toList();
    }

}
