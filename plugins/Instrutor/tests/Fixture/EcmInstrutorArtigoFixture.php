<?php
namespace Instrutor\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmInstrutorArtigoFixture
 *
 */
class EcmInstrutorArtigoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_instrutor_artigo';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_instrutor_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'titulo' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'link' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'FK_ecm_instrutor_artigo_ecm_instrutor' => ['type' => 'index', 'columns' => ['ecm_instrutor_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_instrutor_artigo_ecm_instrutor' => ['type' => 'foreign', 'columns' => ['ecm_instrutor_id'], 'references' => ['ecm_instrutor', 'id'], 'update' => 'restrict', 'delete' => 'cascade', 'length' => []],
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
            'ecm_instrutor_id' => 1,
            'titulo' => 'Lorem ipsum dolor sit amet',
            'link' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
