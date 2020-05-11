<?php
namespace CursoPresencial\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmCursoPresencialLocalFixture
 *
 */
class EcmCursoPresencialLocalFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_curso_presencial_local';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'nome' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'mdl_cidade_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'endereco' => ['type' => 'string', 'length' => 400, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'mdl_cidade_id' => ['type' => 'index', 'columns' => ['mdl_cidade_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_curso_presencial_local_mdl_cidades' => ['type' => 'foreign', 'columns' => ['mdl_cidade_id'], 'references' => ['mdl_cidade', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'nome' => 'Lorem ipsum dolor sit amet',
            'mdl_cidade_id' => 1,
            'endereco' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
