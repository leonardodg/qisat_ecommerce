<?php
namespace Carrinho\Model\Table;

use App\Model\Table\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Carrinho\Model\Entity\EcmVendaStatus;

/**
 * EcmVendaStatus Model
 *
 * @property \Cake\ORM\Association\HasMany $EcmVenda */
class EcmVendaStatusTable extends Table
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

        $this->table('ecm_venda_status');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('EcmVenda', [
            'foreignKey' => 'ecm_venda_status_id',
            'className' => 'Carrinho.EcmVenda'
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
            ->requirePresence('status', 'create')            ->notEmpty('status');
        return $validator;
    }
}
