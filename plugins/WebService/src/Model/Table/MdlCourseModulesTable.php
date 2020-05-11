<?php
namespace WebService\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use WebService\Model\Entity\MdlCourseModule;

/**
 * MdlCourseModules Model
 *
 */
class MdlCourseModulesTable extends Table
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

        $this->table('mdl_course_modules');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('MdlModules', [
            'foreignKey' => 'module',
            'joinType' => 'INNER',
            'className' => 'MdlModules'
        ]);
        $this->belongsTo('MdlForum', [
            'foreignKey' => 'instance',
            'joinType' => 'INNER',
            'className' => 'MdlForum'
        ]);
        $this->belongsTo('MdlFolder', [
            'foreignKey' => 'instance',
            'joinType' => 'INNER',
            'className' => 'MdlFolder'
        ]);

        $this->hasMany('MdlCourseModulesCompletion', [
            'foreignKey' => 'coursemoduleid',
            'className' => 'MdlCourseModulesCompletion'
        ]);

        $this->belongsTo('MdlCourse', [
            'foreignKey' => 'course',
            'className' => 'WebService.MdlCourse'
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
            ->requirePresence('course', 'create')            ->notEmpty('course');
        $validator
            ->requirePresence('module', 'create')            ->notEmpty('module');
        $validator
            ->requirePresence('instance', 'create')            ->notEmpty('instance');
        $validator
            ->requirePresence('section', 'create')            ->notEmpty('section');
        $validator
            ->allowEmpty('idnumber');
        $validator
            ->requirePresence('added', 'create')            ->notEmpty('added');
        $validator
            ->integer('score')            ->requirePresence('score', 'create')            ->notEmpty('score');
        $validator
            ->integer('indent')            ->requirePresence('indent', 'create')            ->notEmpty('indent');
        $validator
            ->boolean('visible')            ->requirePresence('visible', 'create')            ->notEmpty('visible');
        $validator
            ->boolean('visibleold')            ->requirePresence('visibleold', 'create')            ->notEmpty('visibleold');
        $validator
            ->integer('groupmode')            ->requirePresence('groupmode', 'create')            ->notEmpty('groupmode');
        $validator
            ->requirePresence('groupingid', 'create')            ->notEmpty('groupingid');
        $validator
            ->boolean('completion')            ->requirePresence('completion', 'create')            ->notEmpty('completion');
        $validator
            ->allowEmpty('completiongradeitemnumber');
        $validator
            ->boolean('completionview')            ->requirePresence('completionview', 'create')            ->notEmpty('completionview');
        $validator
            ->requirePresence('completionexpected', 'create')            ->notEmpty('completionexpected');
        $validator
            ->boolean('showdescription')            ->requirePresence('showdescription', 'create')            ->notEmpty('showdescription');
        $validator
            ->allowEmpty('availability');
        return $validator;
    }
}
