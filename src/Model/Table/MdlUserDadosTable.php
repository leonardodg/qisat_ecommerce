<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * MdlUserDadosTable Model
 *
 */
class MdlUserDadosTable extends Table
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

        $this->table('mdl_user_dados');
        $this->displayField('id');
        $this->primaryKey('id');

        // $this->schema()->alterColumn('tipo_inscricao_estadual', array(
        //     'nullStrategy' => 'null'
        // ));
    }
}
