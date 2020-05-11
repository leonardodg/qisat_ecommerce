<?php
namespace Vendas\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Vendas\Model\Entity\EcmVendaPresencial;

/**
 * EcmVendaPresencial Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmCursoPresencialTurma * @property \Cake\ORM\Association\BelongsTo $MdlUser */
class EcmVendaPresencialTable extends Table
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

        $this->table('ecm_venda_presencial');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmCursoPresencialTurma', [
            'foreignKey' => 'ecm_curso_presencial_turma_id',
            'joinType' => 'INNER',
            'className' => 'CursoPresencial.EcmCursoPresencialTurma'
        ]);
        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'INNER',
            'className' => 'Vendas.MdlUser'
        ]);
        $this->hasMany('EcmCursoPresencialEmailConfirmacao', [
            'foreignKey' => 'ecm_venda_presencial_id',
            'joinType' => 'INNER',
            'className' => 'Vendas.EcmCursoPresencialEmailConfirmacao'
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
            ->allowEmpty('pedido');
        $validator
            ->dateTime('data')            ->requirePresence('data', 'create')            ->notEmpty('data');
        $validator
            ->integer('quantidade_reserva')            ->allowEmpty('quantidade_reserva');
        $validator
            ->allowEmpty('nome');
        $validator
            ->requirePresence('status', 'create')            ->notEmpty('status');
        $validator
            ->email('email')            ->allowEmpty('email');
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
        $rules->add($rules->existsIn(['ecm_curso_presencial_turma_id'], 'EcmCursoPresencialTurma'));
        $rules->add($rules->existsIn(['mdl_user_id'], 'MdlUser'));
        return $rules;
    }
}
