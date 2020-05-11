<?php
namespace WebService\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use WebService\Model\Entity\MdlCidade;

/**
 * MdlCidade Model
 *
 * @property \Cake\ORM\Association\HasMany $EcmConvenio * @property \Cake\ORM\Association\HasMany $EcmCursoPresencialLocal */
class MdlCidadeTable extends Table
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

        $this->table('mdl_cidade');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('MdlEstado', [
            'foreignKey' => 'uf',
            'joinType' => 'INNER',
            'className' => 'MdlEstado'
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
            ->integer('uf')            ->requirePresence('uf', 'create')            ->notEmpty('uf');
        $validator
            ->requirePresence('nome', 'create')            ->notEmpty('nome');
        return $validator;
    }
}
