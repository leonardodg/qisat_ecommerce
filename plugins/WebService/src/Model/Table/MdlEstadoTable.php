<?php
namespace WebService\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use WebService\Model\Entity\MdlEstado;

/**
 * MdlEstado Model
 *
 */
class MdlEstadoTable extends Table
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

        $this->table('mdl_estado');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('MdlCidade', [
            'foreignKey' => 'uf',
            'joinType' => 'INNER',
            'className' => 'MdlCidade'
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
            ->requirePresence('uf', 'create')            ->notEmpty('uf');
        return $validator;
    }
}
