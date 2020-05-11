<?php
namespace WebService\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MdlUserEnrolmentsFixture
 *
 */
class MdlUserEnrolmentsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'status' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'enrolid' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'userid' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'timestart' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'timeend' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '2147483647', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'modifierid' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'timecreated' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'timemodified' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'mdl_userenro_enr_ix' => ['type' => 'index', 'columns' => ['enrolid'], 'length' => []],
            'mdl_userenro_use_ix' => ['type' => 'index', 'columns' => ['userid'], 'length' => []],
            'mdl_userenro_mod_ix' => ['type' => 'index', 'columns' => ['modifierid'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'mdl_userenro_enruse_uix' => ['type' => 'unique', 'columns' => ['enrolid', 'userid'], 'length' => []],
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
            'status' => 1,
            'enrolid' => 1,
            'userid' => 1,
            'timestart' => 1,
            'timeend' => 1,
            'modifierid' => 1,
            'timecreated' => 1,
            'timemodified' => 1
        ],
    ];
}
