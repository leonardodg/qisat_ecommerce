<?php
namespace Indicacao\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Indicacao\Model\Entity\EcmIndicacaoCurso;

/**
 * EcmIndicacaoCurso Model
 *
 * @property \Cake\ORM\Association\BelongsTo $MdlUser * @property \Cake\ORM\Association\BelongsTo $EcmIndicacaoSegmento * @property \Cake\ORM\Association\BelongsTo $EcmAlternativeHost */
class EcmIndicacaoCursoTable extends Table
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

        $this->table('ecm_indicacao_curso');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'className' => 'MdlUser'
        ]);
        $this->belongsTo('EcmIndicacaoSegmento', [
            'foreignKey' => 'ecm_indicacao_segmento_id',
            'joinType' => 'INNER',
            'className' => 'Indicacao.EcmIndicacaoSegmento'
        ]);
        $this->belongsTo('EcmAlternativeHost', [
            'foreignKey' => 'ecm_alternative_host_id',
            'joinType' => 'INNER',
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
            ->allowEmpty('id', 'create');
        $validator
            ->requirePresence('tema', 'create')            ->notEmpty('tema');
        $validator
            ->dateTime('timemodified')            ->requirePresence('timemodified', 'create')            ->notEmpty('timemodified');
        $validator
            ->allowEmpty('nome_base_antiga');
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
        $rules->add($rules->existsIn(['mdl_user_id'], 'MdlUser'));
        $rules->add($rules->existsIn(['ecm_indicacao_segmento_id'], 'EcmIndicacaoSegmento'));
        $rules->add($rules->existsIn(['ecm_alternative_host_id'], 'EcmAlternativeHost'));
        return $rules;
    }
}
