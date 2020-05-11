<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmLogAcaoFixture
 *
 */
class EcmLogAcaoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_log_acao';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'mdl_user_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'tabela' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'acao' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'chave' => ['type' => 'string', 'length' => 20, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'data' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'ip' => ['type' => 'string', 'length' => 36, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'FK_ecm_log_acao_mdl_user' => ['type' => 'index', 'columns' => ['mdl_user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_log_acao_mdl_user' => ['type' => 'foreign', 'columns' => ['mdl_user_id'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
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
            'mdl_user_id' => 1,
            'tabela' => 'Lorem ipsum dolor sit amet',
            'acao' => 'Lorem ipsum dolor sit amet',
            'chave' => 'Lorem ipsum dolor ',
            'data' => '2016-08-16 11:38:49',
            'ip' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
