<?php
namespace Entidade\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmAlternativeHostFixture
 *
 */
class EcmAlternativeHostFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_alternative_host';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'host' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'shortname' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fullname' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'path' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => '', 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'googleanalytics' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'codigoorigemaltoqi' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'host' => ['type' => 'unique', 'columns' => ['host'], 'length' => []],
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
            'host' => 'Lorem ipsum dolor sit amet',
            'shortname' => 'Lorem ipsum dolor sit amet',
            'fullname' => 'Lorem ipsum dolor sit amet',
            'path' => 'Lorem ipsum dolor sit amet',
            'email' => 'Lorem ipsum dolor sit amet',
            'googleanalytics' => 'Lorem ipsum dolor sit amet',
            'codigoorigemaltoqi' => 1
        ],
    ];
}
