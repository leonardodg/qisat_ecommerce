<?php
namespace Entidade\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MdlUserEcmAlternativeHostFixture
 *
 */
class MdlUserEcmAlternativeHostFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'mdl_user_ecm_alternative_host';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'mdl_user_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_alternative_host_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'numero' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'adimplente' => ['type' => 'integer', 'length' => 1, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_mdl_user_ecm_alternative_host_ecm_alternative_host' => ['type' => 'index', 'columns' => ['ecm_alternative_host_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['mdl_user_id', 'ecm_alternative_host_id'], 'length' => []],
            'FK__mdl_user' => ['type' => 'foreign', 'columns' => ['mdl_user_id'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_mdl_user_ecm_alternative_host_ecm_alternative_host' => ['type' => 'foreign', 'columns' => ['ecm_alternative_host_id'], 'references' => ['ecm_alternative_host', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'mdl_user_id' => 1,
            'ecm_alternative_host_id' => 1,
            'numero' => 'Lorem ipsum dolor sit amet',
            'adimplente' => 1
        ],
    ];
}
