<?php
namespace Publicidade\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Publicidade\Model\Entity\EcmPublicidade;

/**
 * EcmPublicidade Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmProduto */
class EcmPublicidadeTable extends Table
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

        $this->table('ecm_publicidade');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_id',
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
            ->requirePresence('nome', 'create')            ->notEmpty('nome');
        $validator
            ->requirePresence('src', 'create')            ->notEmpty('src');
        $validator
            ->requirePresence('arquivo', 'create')            ->notEmpty('arquivo');
        $validator
            ->requirePresence('tipo', 'create')            ->notEmpty('tipo');
        $validator
            ->boolean('habilitado')            ->requirePresence('habilitado', 'create')            ->notEmpty('habilitado');
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
        return $rules;
    }

    /**
     * EnviarArquivo method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function enviarArquivo($request, $id, $antigo)
    {
        if(!empty($request['tmp_name'])){
            $diretorio = WWW_ROOT . 'upload/publicidade';
            if(!file_exists($diretorio))
                mkdir($diretorio);
            $diretorio .= '/' . $id;
            if(!file_exists($diretorio))
                mkdir($diretorio);
            if(isset($antigo) && file_exists($diretorio. '/' . $antigo))
                unlink($diretorio. '/' . $antigo);
            return move_uploaded_file($request['tmp_name'], $diretorio . '/' . $request['name']);
        }
        return isset($antigo);
    }
}
