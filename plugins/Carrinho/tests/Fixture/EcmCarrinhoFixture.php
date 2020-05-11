<?php
namespace Carrinho\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmCarrinhoFixture
 *
 */
class EcmCarrinhoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_carrinho';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'data' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'mdl_user_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'status' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'ecm_cupom_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'edicao' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'ecm_alternative_host_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_user_modified' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_ecm_carrinho_ecm_alternative_host' => ['type' => 'index', 'columns' => ['ecm_alternative_host_id'], 'length' => []],
            'FK_ecm_carrinho_mdl_user' => ['type' => 'index', 'columns' => ['mdl_user_id'], 'length' => []],
            'FK_ecm_carrinho_mdl_user_2' => ['type' => 'index', 'columns' => ['ecm_user_modified'], 'length' => []],
            'FK_ecm_carrinho_ecm_carrinho' => ['type' => 'index', 'columns' => ['ecm_cupom_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_carrinho_ecm_alternative_host' => ['type' => 'foreign', 'columns' => ['ecm_alternative_host_id'], 'references' => ['ecm_alternative_host', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_carrinho_ecm_carrinho' => ['type' => 'foreign', 'columns' => ['ecm_cupom_id'], 'references' => ['ecm_cupom', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_carrinho_mdl_user' => ['type' => 'foreign', 'columns' => ['mdl_user_id'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_carrinho_mdl_user_2' => ['type' => 'foreign', 'columns' => ['ecm_user_modified'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
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
            'data' => '2016-05-19 10:38:39',
            'mdl_user_id' => 1,
            'status' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'ecm_cupom_id' => 1,
            'edicao' => '2016-05-19 10:38:39',
            'ecm_alternative_host_id' => 1,
            'ecm_user_modified' => 1
        ],
    ];
}
