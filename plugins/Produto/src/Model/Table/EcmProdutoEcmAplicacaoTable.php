<?php
namespace Produto\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Produto\Model\Entity\EcmProdutoEcmAplicacao;

/**
 * EcmProdutoEcmAplicacao Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmProdutos * @property \Cake\ORM\Association\BelongsTo $EcmAplicacaos * @property \Cake\ORM\Association\HasMany $EcmCarrinhoItemEcmProdutoAplicacao */
class EcmProdutoEcmAplicacaoTable extends Table
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

        $this->table('ecm_produto_ecm_aplicacao');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmProduto'
        ]);

        $this->belongsTo('EcmProdutoAplicacao', [
            'foreignKey' => 'ecm_aplicacao_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmProdutoAplicacao'
        ]);

        $this->hasMany('EcmCarrinhoItemEcmProdutoAplicacao', [
            'foreignKey' => 'ecm_produto_ecm_aplicacao_id',
            'className' => 'Produto.EcmCarrinhoItemEcmProdutoAplicacao'
        ]);

        $this->belongsToMany('EcmCarrinhoItem', [
            'ForeignKey' => 'ecm_produto_ecm_aplicacao_id',
            'targetforeignKey' => 'ecm_carrinho_item_id',
            'joinTable' => 'ecm_carrinho_item_ecm_produto_aplicacao',
            'className' => 'Carrinho.EcmCarrinhoItem'
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
            ->integer('edicao')            ->requirePresence('edicao', 'create')            ->notEmpty('edicao');
        $validator
            ->requirePresence('codigo_tw', 'create')            ->notEmpty('codigo_tw');
        $validator
            ->integer('ativo')            ->requirePresence('ativo', 'create')            ->notEmpty('ativo');
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
        $rules->add($rules->existsIn(['ecm_produto_id'], 'EcmProduto'));
        $rules->add($rules->existsIn(['ecm_aplicacao_id'], 'EcmProdutoAplicacao'));
        return $rules;
    }
}
