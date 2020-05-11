<?php
namespace Repasse\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmRepasseFixture
 *
 */
class EcmRepasseFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_repasse';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'assunto_email' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'corpo_email' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'chave' => ['type' => 'string', 'length' => 8, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'data_registro' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'mdl_user_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => 'Atendente do repasse', 'precision' => null, 'autoIncrement' => null],
        'mdl_usermodified_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => 'Atendente modificador do repasse', 'precision' => null, 'autoIncrement' => null],
        'equipe' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => 'QiSat', 'comment' => '', 'precision' => null],
        'data_modificacao' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'status' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'ecm_alternative_host_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'observacao' => ['type' => 'string', 'length' => 250, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'ecm_repasse_categorias_id' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_repasse_origem_id' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_ecm_repasse_mdl_user' => ['type' => 'index', 'columns' => ['mdl_user_id'], 'length' => []],
            'FK_ecm_repasse_mdl_user_2' => ['type' => 'index', 'columns' => ['mdl_usermodified_id'], 'length' => []],
            'FK_ecm_repasse_ecm_alternative_host' => ['type' => 'index', 'columns' => ['ecm_alternative_host_id'], 'length' => []],
            'FK_ecm_repasse_ecm_repasse_categorias' => ['type' => 'index', 'columns' => ['ecm_repasse_categorias_id'], 'length' => []],
            'FK_ecm_repasse_ecm_repasse_origem' => ['type' => 'index', 'columns' => ['ecm_repasse_origem_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_repasse_ecm_alternative_host' => ['type' => 'foreign', 'columns' => ['ecm_alternative_host_id'], 'references' => ['ecm_alternative_host', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_repasse_ecm_repasse_categorias' => ['type' => 'foreign', 'columns' => ['ecm_repasse_categorias_id'], 'references' => ['ecm_repasse_categorias', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_repasse_ecm_repasse_origem' => ['type' => 'foreign', 'columns' => ['ecm_repasse_origem_id'], 'references' => ['ecm_repasse_origem', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_repasse_mdl_user' => ['type' => 'foreign', 'columns' => ['mdl_user_id'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_repasse_mdl_user_2' => ['type' => 'foreign', 'columns' => ['mdl_usermodified_id'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'assunto_email' => 'Lorem ipsum dolor sit amet',
            'corpo_email' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'chave' => 'Lorem ',
            'data_registro' => '2018-01-25 12:02:17',
            'mdl_user_id' => 1,
            'mdl_usermodified_id' => 1,
            'equipe' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'data_modificacao' => '2018-01-25 12:02:17',
            'status' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'ecm_alternative_host_id' => 1,
            'observacao' => 'Lorem ipsum dolor sit amet',
            'ecm_repasse_categorias_id' => 1,
            'ecm_repasse_origem_id' => 1
        ],
    ];
}
