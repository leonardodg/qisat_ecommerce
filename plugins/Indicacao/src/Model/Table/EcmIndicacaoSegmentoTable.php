<?php
namespace Indicacao\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Indicacao\Model\Entity\EcmIndicacaoSegmento;

/**
 * EcmIndicacaoSegmento Model
 *
 * @property \Cake\ORM\Association\HasMany $EcmIndicacaoCurso */
class EcmIndicacaoSegmentoTable extends Table
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

        $this->table('ecm_indicacao_segmento');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('EcmIndicacaoCurso', [
            'foreignKey' => 'ecm_indicacao_segmento_id',
            'className' => 'Indicacao.EcmIndicacaoCurso'
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
            ->requirePresence('segmento', 'create')            ->notEmpty('segmento');
        return $validator;
    }
}
