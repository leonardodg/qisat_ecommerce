<?php
namespace CursoPresencial\Model\Table;

use App\Model\Table\Table;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use CursoPresencial\Model\Entity\EcmCursoPresencialTurma;

/**
 * EcmCursoPresencialTurma Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmProduto * @property \Cake\ORM\Association\HasMany $EcmCursoPresencialData * @property \Cake\ORM\Association\BelongsToMany $EcmInstrutor */
class EcmCursoPresencialTurmaTable extends Table
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

        $this->table('ecm_curso_presencial_turma');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmProduto'
        ]);
        $this->hasMany('EcmCursoPresencialData', [
            'foreignKey' => 'ecm_curso_presencial_turma_id',
            'className' => 'CursoPresencial.EcmCursoPresencialData'
        ]);
        $this->belongsToMany('EcmInstrutor', [
            'foreignKey' => 'ecm_curso_presencial_turma_id',
            'targetForeignKey' => 'ecm_instrutor_id',
            'joinTable' => 'ecm_curso_presencial_turma_ecm_instrutor',
            'className' => 'Instrutor.EcmInstrutor'
        ]);
        $this->EcmInstrutor->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'INNER',
            'className' => 'CursoPresencial.MdlUser'
        ]);
        $this->EcmCursoPresencialData->belongsTo('EcmCursoPresencialLocal', [
            'foreignKey' => 'ecm_curso_presencial_local_id',
            'joinType' => 'INNER',
            'className' => 'CursoPresencial.EcmCursoPresencialLocal'
        ]);
        $this->belongsTo('EcmCursoPresencialTurmaEcmInstrutor', [
            'foreignKey' => 'id',
            'targetForeignKey' => 'ecm_curso_presencial_turma_id',
            'joinType' => 'INNER',
            'className' => 'CursoPresencial.EcmCursoPresencialTurmaEcmInstrutor'
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
            ->integer('carga_horaria')            ->requirePresence('carga_horaria', 'create')            ->notEmpty('carga_horaria');
        $validator
            ->integer('vagas_total')            ->requirePresence('vagas_total', 'create')            ->notEmpty('vagas_total');
        $validator
            ->integer('vagas_preenchidas')            ->requirePresence('vagas_preenchidas', 'create')            ->notEmpty('vagas_preenchidas');
        $validator
            ->decimal('valor')            ->allowEmpty('valor');
        $validator
            ->requirePresence('valor_produto', 'create')            ->notEmpty('valor_produto');
        $validator
            ->requirePresence('status', 'create')            ->notEmpty('status');
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

    public function buscaEstadoPeloCurso(\DateTime $data){
        $sql = "
                    SELECT e.nome as estado, e.uf, CONCAT(e.nome, ' - ', e.uf) as local,

                    (
                        SELECT GROUP_CONCAT(DISTINCT cpl1.id)
                        FROM mdl_estado e1
                        INNER JOIN mdl_cidade c1 ON c1.uf = e1.id
                        INNER JOIN ecm_curso_presencial_local cpl1 ON cpl1.mdl_cidade_id = c1.id
                        INNER JOIN ecm_curso_presencial_data cpd1 ON cpd1.ecm_curso_presencial_local_id = cpl1.id
                        INNER JOIN ecm_curso_presencial_turma cpt1 ON cpt1.id = cpd1.ecm_curso_presencial_turma_id
                        WHERE cpt1.`status` = 'Ativo' AND cpd1.datainicio > '2017-01-03 00:00:00' AND
                            e1.id = e.id
                    ) AS edicao
                    FROM mdl_estado e
                    INNER JOIN mdl_cidade c ON c.uf = e.id
                    INNER JOIN ecm_curso_presencial_local cpl ON cpl.mdl_cidade_id = c.id
                    INNER JOIN ecm_curso_presencial_data cpd ON cpd.ecm_curso_presencial_local_id = cpl.id
                    INNER JOIN ecm_curso_presencial_turma cpt ON cpt.id = cpd.ecm_curso_presencial_turma_id
                    WHERE cpt.`status` = 'Ativo' AND cpd.datainicio > '2017-01-03 00:00:00'
                    GROUP BY e.id";

        $conexao = ConnectionManager::get('default');
        return $conexao->execute($sql)->fetchAll('assoc');
    }
}
