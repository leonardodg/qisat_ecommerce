<?php
namespace App\Model\Table;

use Cake\Controller\Component\AuthComponent;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Validation\Validator;

/**
 * EcmPermissao Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $EcmGrupoPermissao */
class EcmPermissaoTable extends Table
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

        $this->table('ecm_permissao');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsToMany('EcmGrupoPermissao', [
            'foreignKey' => 'ecm_permissao_id',
            'targetForeignKey' => 'ecm_grupo_permissao_id',
            'joinTable' => 'ecm_grupo_permissao_ecm_permissao'
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
            ->requirePresence('action', 'create')            ->notEmpty('action');
        $validator
            ->requirePresence('controller', 'create')            ->notEmpty('controller');
        $validator
            ->requirePresence('label', 'create')            ->notEmpty('label');
        $validator
            ->requirePresence('descricao', 'create')            ->notEmpty('descricao');
        $validator
            ->requirePresence('restricao', 'create')            ->notEmpty('restricao');
        return $validator;
    }

    public function buscarPermissoesUsuario($idUser){
        return $this->buscarPermissoes($idUser);
    }

    public function buscarPermissoesPorRestricao($restricao){
        return $this->buscarPermissoes(0, $restricao);
    }

    private function buscarPermissoes($idUser, $restricao = null){
        $query = $this->find('all')->distinct('EcmPermissao.id')
            ->leftJoin('ecm_grupo_permissao_ecm_permissao',
                ['ecm_grupo_permissao_ecm_permissao.ecm_permissao_id = EcmPermissao.id'])
            ->leftJoin('ecm_grupo_permissao',
                ['ecm_grupo_permissao.id = ecm_grupo_permissao_ecm_permissao.ecm_grupo_permissao_id'])
            ->leftJoin('ecm_grupo_permissao_mdl_user',
                ['ecm_grupo_permissao_mdl_user.ecm_grupo_permissao_id = ecm_grupo_permissao.id'])
            ->leftJoin('mdl_user', ['mdl_user.id = ecm_grupo_permissao_mdl_user.mdl_user_id']);


        if(!is_null($restricao)){
            $query->where(['EcmPermissao.restricao' => $restricao]);
        }else{
            $query->where(['OR'=>['mdl_user.id'=>$idUser, 'EcmPermissao.restricao IN ' => ['login', 'site']]]);
        }

        $listaPermissoes = $query->toArray();
        $permissoes = array();

        if(count($listaPermissoes) > 0){
            foreach ($listaPermissoes as $permissao) {
                $permissoes[$permissao->plugin][$permissao->controller][$permissao->action] = $permissao->label;
            }
        }

        return $permissoes;
    }
}
