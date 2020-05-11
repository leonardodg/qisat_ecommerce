<?php
namespace CursoPresencial\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use CursoPresencial\Model\Entity\EcmCursoPresencialLocal;

/**
 * EcmCursoPresencialLocal Model
 *
 * @property \Cake\ORM\Association\BelongsTo $MdlCidade */
class EcmCursoPresencialLocalTable extends Table
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

        $this->table('ecm_curso_presencial_local');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('MdlCidade', [
            'foreignKey' => 'mdl_cidade_id',
            'joinType' => 'INNER',
            'className' => 'CursoPresencial.MdlCidade'
        ]);

        $this->MdlCidade->belongsTo('MdlEstado', [
            'foreignKey' => 'uf',
            'joinType' => 'INNER',
            'className' => 'CursoPresencial.MdlEstado'
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
            ->requirePresence('nome', 'create')            ->notEmpty('nome');
        $validator
            ->allowEmpty('endereco');
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
        $rules->add($rules->existsIn(['mdl_cidade_id'], 'MdlCidade'));
        return $rules;
    }
}
