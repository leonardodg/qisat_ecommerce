<?php
namespace App\Model\Table;

use App\Model\Entity\EcmGrupoPermissao;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * EcmGrupoPermissao Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $EcmPermissao * @property \Cake\ORM\Association\BelongsToMany $MdlUser */
class EcmGrupoPermissaoTable extends Table
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

        $this->table('ecm_grupo_permissao');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsToMany('EcmPermissao', [
            'foreignKey' => 'ecm_grupo_permissao_id',
            'targetForeignKey' => 'ecm_permissao_id',
            'joinTable' => 'ecm_grupo_permissao_ecm_permissao'
        ]);
        $this->belongsToMany('MdlUser', [
            'foreignKey' => 'ecm_grupo_permissao_id',
            'targetForeignKey' => 'mdl_user_id',
            'joinTable' => 'ecm_grupo_permissao_mdl_user'
        ]);

        $this->belongsTo('EcmAlternativeHost', [
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
            ->requirePresence('descricao', 'create')            ->notEmpty('descricao');
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

    public function verificarAcessoTotalUsuario($userId){
        $query = $this->find('all',['fields'=>['EcmGrupoPermissao.id']])
            ->leftJoin('ecm_grupo_permissao_mdl_user',
                ['ecm_grupo_permissao_mdl_user.ecm_grupo_permissao_id = EcmGrupoPermissao.id'])
            ->leftJoin('mdl_user',
                ['mdl_user.id = ecm_grupo_permissao_mdl_user.mdl_user_id'])
            ->where(['EcmGrupoPermissao.acesso_total' => 1, 'mdl_user.id' => $userId])
            ->limit(1);

        return $query->count();
    }
}
