<?php
namespace Instrutor\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmInstrutorRedeSocialFixture
 *
 */
class EcmInstrutorRedeSocialFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_instrutor_rede_social';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_instrutor_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_rede_social_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'link' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'FK_ecm_instrutor_rede_social_ecm_instrutor' => ['type' => 'index', 'columns' => ['ecm_instrutor_id'], 'length' => []],
            'FK_ecm_instrutor_rede_social_ecm_rede_social' => ['type' => 'index', 'columns' => ['ecm_rede_social_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_instrutor_rede_social_ecm_instrutor' => ['type' => 'foreign', 'columns' => ['ecm_instrutor_id'], 'references' => ['ecm_instrutor', 'id'], 'update' => 'restrict', 'delete' => 'cascade', 'length' => []],
            'FK_ecm_instrutor_rede_social_ecm_rede_social' => ['type' => 'foreign', 'columns' => ['ecm_rede_social_id'], 'references' => ['ecm_rede_social', 'id'], 'update' => 'restrict', 'delete' => 'cascade', 'length' => []],
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
            'ecm_rede_social_id' => 1,
            'link' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
