<?php
namespace Vendas\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Vendas\Model\Entity\EcmCursoPresencialEmailConfirmacao;

/**
 * EcmCursoPresencialEmailConfirmacao Model
 *
 * @property \Cake\ORM\Association\BelongsTo $EcmVendaPresencial * @property \Cake\ORM\Association\BelongsTo $EcmVenda */
class EcmCursoPresencialEmailConfirmacaoTable extends Table
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

        $this->table('ecm_curso_presencial_email_confirmacao');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('EcmVendaPresencial', [
            'foreignKey' => 'ecm_venda_presencial_id',
            'className' => 'Vendas.EcmVendaPresencial'
        ]);
        $this->belongsTo('EcmVenda', [
            'foreignKey' => 'ecm_venda_id',
            'className' => 'Vendas.EcmVenda'
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
            ->boolean('enviado')            ->requirePresence('enviado', 'create')            ->notEmpty('enviado');
        $validator
            ->dateTime('data_envio')            ->requirePresence('data_envio', 'create')            ->notEmpty('data_envio');
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
        $rules->add($rules->existsIn(['ecm_venda_presencial_id'], 'EcmVendaPresencial'));
        $rules->add($rules->existsIn(['ecm_venda_id'], 'EcmVenda'));
        return $rules;
    }
}
