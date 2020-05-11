<?php
namespace Produto\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Produto\Model\Entity\MdlFase;

/**
 * MdlFase Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmProduto * @property \Cake\ORM\Association\HasMany $MdlGroups * @property \Cake\ORM\Association\BelongsToMany $MdlCourse */
class MdlFaseTable extends Table
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

        $this->table('mdl_fase');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_id',
            'joinType' => 'INNER',
            'className' => 'Produto.EcmProduto'
        ]);
        $this->hasMany('MdlGroups', [
            'foreignKey' => 'mdl_fase_id',
            'className' => 'Produto.MdlGroups'
        ]);
        $this->belongsToMany('MdlCourse', [
            'foreignKey' => 'mdl_fase_id',
            'targetForeignKey' => 'mdl_course_id',
            'joinTable' => 'mdl_course_mdl_fase',
            'className' => 'Produto.MdlCourse'
        ]);
        $this->MdlCourse->belongsToMany('EcmProduto', [
            'foreignKey' => 'mdl_course_id',
            'targetForeignKey' => 'ecm_produto_id',
            'joinTable' => 'ecm_produto_mdl_course',
            'className' => 'Produto.EcmProduto'
        ]);

        $this->hasMany('MdlCourseMdlFase', [
            'className' => 'Produto.MdlCourseMdlFase'
        ]);

        $this->hasMany('MdlFaseRanking', [
            'foreignKey' => 'mdl_fase_id',
            'className' => 'Produto.MdlFaseRanking'
        ]);
        $this->MdlFaseRanking->hasOne('MdlUser', [
            'foreignKey' => 'id',
            'bindingKey' => 'mdl_user_id',
            'className' => 'MdlUser'
        ]);

        $this->MdlFaseRanking->belongsTo('MdlFase', [
            'foreignKey' => 'mdl_fase_id',
            'className' => 'Produto.MdlFase'
        ]);
        $this->hasMany('MdlFaseConclusao', [
            'className' => 'Produto.MdlFaseConclusao'
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
            ->requirePresence('descricao', 'create')            ->notEmpty('descricao');
        $validator
            ->decimal('valor_carga_horaria')            ->requirePresence('valor_carga_horaria', 'create')            ->notEmpty('valor_carga_horaria');
        $validator
            ->requirePresence('enrolperiod', 'create')            ->notEmpty('enrolperiod');
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

    /*
     * Função responsável por buscar os descontos referente aos cursos de uma fase
     *
     * @param int $idUsuario
     * @param int $idProduto
     *
     * @return array
     *
     * */
    public function buscarDescontos($idUsuario, $idProduto){

        $descontos = $this->find('all',[
                'fields' => [
                    'fullname' => 'mdl_course.fullname',
                    'coursehours' => 'mdl_course.coursehours',
                    'valor_carga_horaria' => 'MdlFase.valor_carga_horaria',
                    'desconto' => '(mdl_course.coursehours * MdlFase.valor_carga_horaria)'
                ]
            ])
            ->leftJoin('ecm_produto_mdl_course', [
                'ecm_produto_mdl_course.ecm_produto_id = MdlFase.ecm_produto_id'
            ])
            ->leftJoin('mdl_course', [
                'ecm_produto_mdl_course.mdl_course_id = mdl_course.id'
            ])
            ->leftJoin('mdl_enrol', [
                'mdl_course.id = mdl_enrol.courseid'
            ])
            ->leftJoin('mdl_user_enrolments', [
                'mdl_enrol.id = mdl_user_enrolments.enrolid'
            ])
            ->leftJoin('mdl_user', [
                'mdl_user_enrolments.userid = mdl_user.id'
            ])
            ->where([
                'mdl_user.id' => $idUsuario,
                'ecm_produto_mdl_course.ecm_produto_id' => $idProduto,
                'MdlFase.valor_carga_horaria >' => 0
            ])
            ->toList();

        return $descontos;

    }
}
