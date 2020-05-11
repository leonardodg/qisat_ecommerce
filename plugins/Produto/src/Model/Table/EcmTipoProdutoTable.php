<?php
namespace Produto\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Produto\Model\Entity\EcmTipoProduto;

/**
 * EcmTipoProduto Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmTipoProduto * @property \Cake\ORM\Association\HasMany $EcmTipoProduto * @property \Cake\ORM\Association\BelongsToMany $EcmProduto */
class EcmTipoProdutoTable extends Table
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

        $this->table('ecm_tipo_produto');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasOne('EcmTipoProduto', [
            'foreignKey' => 'ecm_tipo_produto_id',
            'className' => 'Produto.EcmTipoProduto'
        ]);
        $this->hasMany('EcmTipoProduto', [
            'foreignKey' => 'ecm_tipo_produto_id',
            'className' => 'Produto.EcmTipoProdutoAR'
        ]);
        $this->belongsToMany('EcmProduto', [
            'foreignKey' => 'ecm_tipo_produto_id',
            'targetForeignKey' => 'ecm_produto_id',
            'joinTable' => 'ecm_produto_ecm_tipo_produto',
            'className' => 'Produto.EcmProduto'
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
            ->requirePresence('nome', 'create')            ->notEmpty('nome')            ->add('nome', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);
        $validator
            ->allowEmpty('ordem');
        $validator
            ->requirePresence('habilitado', 'create')            ->notEmpty('habilitado');
        $validator
            ->requirePresence('blocked', 'create')            ->notEmpty('blocked');
        $validator
            ->allowEmpty('categoria');
        $validator
            ->allowEmpty('theme');
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
}
