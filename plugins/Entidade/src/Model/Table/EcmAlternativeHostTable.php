<?php
namespace Entidade\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Entidade\Model\Entity\EcmAlternativeHost;

/**
 * EcmAlternativeHost Model
 *
 * @property \Cake\ORM\Association\HasMany $EcmCarrinho * @property \Cake\ORM\Association\HasMany $EcmCupom * @property \Cake\ORM\Association\BelongsToMany $EcmProdutoEcmTipoProduto * @property \Cake\ORM\Association\BelongsToMany $EcmPromocao * @property \Cake\ORM\Association\BelongsToMany $MdlUser */
class EcmAlternativeHostTable extends Table
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

        $this->table('ecm_alternative_host');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('EcmCarrinho', [
            'foreignKey' => 'ecm_alternative_host_id',
            'className' => 'Entidade.EcmCarrinho'
        ]);
        $this->hasMany('EcmCupom', [
            'foreignKey' => 'ecm_alternative_host_id',
            'className' => 'Entidade.EcmCupom'
        ]);

        /*$this->belongsToMany('EcmProdutoEcmTipoProduto', [
            'foreignKey' => 'ecm_alternative_host_id',
            'targetForeignKey' => 'ecm_produto_ecm_tipo_produto_id',
            'joinTable' => 'ecm_produto_ecm_tipo_produto_ecm_alternative_host',
            'className' => 'Entidade.EcmProdutoEcmTipoProduto'
        ]);*/

        $this->belongsToMany('EcmPromocao', [
            'foreignKey' => 'ecm_alternative_host_id',
            'targetForeignKey' => 'ecm_promocao_id',
            'joinTable' => 'ecm_promocao_ecm_alternative_host',
            'className' => 'Promocao.EcmPromocao'
        ]);
        $this->belongsToMany('MdlUser', [
            'foreignKey' => 'ecm_alternative_host_id',
            'targetForeignKey' => 'mdl_user_id',
            'joinTable' => 'mdl_user_ecm_alternative_host',
            'className' => 'MdlUser'
        ]);
        $this->belongsToMany('EcmImagem', [
            'foreignKey' => 'ecm_alternative_host_id',
            'targetForeignKey' => 'ecm_imagem_id',
            'joinTable' => 'ecm_alternative_host_ecm_imagem',
            'className' => 'Imagem.EcmImagem'
        ]);

        $this->hasMany('EcmProdutoEcmTipoProdutoEcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
            'className' => 'Entidade.EcmProdutoEcmTipoProdutoEcmAlternativeHost'
        ]);
        $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->hasOne('EcmProdutoEcmTipoProduto', [
            'foreignKey' => 'id',
            'bindingKey' => 'ecm_produto_ecm_tipo_produto_id',
            'className' => 'Entidade.EcmProdutoEcmTipoProduto'
        ]);
        $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->EcmProdutoEcmTipoProduto->belongsTo('EcmProduto', [
            'className' => 'Produto.EcmProduto'
        ]);
        $this->EcmProdutoEcmTipoProdutoEcmAlternativeHost->EcmProdutoEcmTipoProduto->belongsTo('EcmTipoProduto', [
            'className' => 'Produto.EcmTipoProduto'
        ]);

        $this->hasMany('MdlUserEcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
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
            ->allowEmpty('id', 'create');
        $validator
            ->requirePresence('host', 'create')            ->notEmpty('host')            ->add('host', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);
        $validator
            ->requirePresence('shortname', 'create')            ->notEmpty('shortname');
        $validator
            ->requirePresence('fullname', 'create')            ->notEmpty('fullname');
        $validator
            ->requirePresence('path', 'create')            ->notEmpty('path');
        $validator
            ->email('email')            ->allowEmpty('email');
        $validator
            ->allowEmpty('googleanalytics');
        $validator
            ->integer('codigoorigemaltoqi')            ->allowEmpty('codigoorigemaltoqi');
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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['host']));
        return $rules;
    }
}
