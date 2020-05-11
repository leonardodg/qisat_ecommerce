<?php
namespace CursoPresencial\Model\Table;

use App\Model\Table\Table;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use CursoPresencial\Model\Entity\EcmCursoPresencialInteresse;

/**
 * EcmCursoPresencialInteresse Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmCursoPresencialTurma * @property \Cake\ORM\Association\BelongsTo $EcmProduto */
class EcmCursoPresencialInteresseTable extends Table
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

        $this->table('ecm_curso_presencial_interesse');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmCursoPresencialTurma', [
            'foreignKey' => 'ecm_curso_presencial_turma_id',
            'joinType' => 'LEFT',
            'className' => 'CursoPresencial.EcmCursoPresencialTurma'
        ]);
        $this->belongsTo('EcmProduto', [
            'foreignKey' => 'ecm_produto_id',
            'joinType' => 'LEFT',
            'className' => 'Produto.EcmProduto'
        ]);
        $this->belongsTo('MdlUser', [
            'foreignKey' => 'mdl_user_id',
            'joinType' => 'LEFT',
            'className' => 'MdlUser'
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
            ->email('email')            ->requirePresence('email', 'create')            ->notEmpty('email');
        $validator
            ->requirePresence('telefone', 'create')            ->notEmpty('telefone');
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
        $rules->add($rules->existsIn(['ecm_produto_id'], 'EcmProduto'));
        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options){
        $entity = $event->data['entity'];

        if(is_null($entity->id)){
            $entity->set('data', new \DateTime());
        }

        return true;
    }
}
