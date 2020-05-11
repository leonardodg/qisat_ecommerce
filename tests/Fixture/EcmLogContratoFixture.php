<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmLogContratoFixture
 *
 */
class EcmLogContratoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_log_contrato';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_venda_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'mdl_user_enrolments_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'timecreated' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'mdl_user_enrolments_id' => ['type' => 'index', 'columns' => ['mdl_user_enrolments_id'], 'length' => []],
            'ecm_venda_id' => ['type' => 'index', 'columns' => ['ecm_venda_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
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
            'ecm_venda_id' => 1,
            'mdl_user_enrolments_id' => 1,
            'timecreated' => 1
        ],
    ];
}
