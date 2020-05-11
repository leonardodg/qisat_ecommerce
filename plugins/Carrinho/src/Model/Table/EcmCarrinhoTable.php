<?php
namespace Carrinho\Model\Table;

use App\Model\Table\Table;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Carrinho\Model\Entity\EcmCarrinho;

/**
 * EcmCarrinho Model
 *
 * @property \Cake\ORM\Association\BelongsTo $MdlUser * @property \Cake\ORM\Association\BelongsTo $EcmCupom * @property \Cake\ORM\Association\BelongsTo $EcmAlternativeHost * @property \Cake\ORM\Association\HasMany $EcmCarrinhoItem * @property \Cake\ORM\Association\BelongsToMany $EcmPromocao */
class EcmCarrinhoTable extends Table
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

        $this->table('ecm_carrinho');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'INNER',
            'className' => 'MdlUser'
        ]);
        $this->belongsTo('EcmCupom', [
            'foreignKey' => 'ecm_cupom_id',
            'className' => 'Cupom.EcmCupom'
        ]);
        $this->belongsTo('EcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
            'className' => 'Entidade.EcmAlternativeHost'
        ]);
        $this->hasMany('EcmCarrinhoItem', [
            'foreignKey' => 'ecm_carrinho_id',
            'className' => 'Carrinho.EcmCarrinhoItem'
        ]);
        $this->EcmCarrinhoItem->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_id',
            'className' => 'Produto.EcmProduto'
        ]);
        $this->EcmCarrinhoItem->belongsTo('EcmPromocao', [
            'foreignKey' => 'ecm_promocao_id',
            'className' => 'Promocao.EcmPromocao'
        ]);
        $this->EcmCarrinhoItem->belongsTo('EcmCupom', [
            'foreignKey' => 'ecm_cupom_id',
            'className' => 'Cupom.EcmCupom'
        ]);

        $this->belongsTo('EcmVenda', [
            'foreignKey' => 'id',
            'bindingKey' => 'ecm_carrinho_id',
            'joinType' => 'INNER',
            'className' => 'Vendas.EcmVenda'
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
            ->requirePresence('status', 'create')            ->notEmpty('status');
        $validator
            ->dateTime('edicao')            ->allowEmpty('edicao');
        $validator
            ->allowEmpty('ecm_user_modified');
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
        $rules->add($rules->existsIn(['ecm_cupom_id'], 'EcmCupom'));
        $rules->add($rules->existsIn(['ecm_alternative_host_id'], 'EcmAlternativeHost'));
        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options){
        $entity = $event->data['entity'];

        if(is_null($entity->id)){
            $entity->set('data', new \DateTime());
        }
        $edicao = new \DateTime();
        if(is_null($entity->edicao) || $entity->edicao < $edicao){
            $entity->set('edicao', new \DateTime());
        }
    }
}
