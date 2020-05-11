<?php
namespace Produto\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmProdutoTipoProdutoEcmAlternativeHostFixture
 *
 */
class EcmProdutoTipoProdutoEcmAlternativeHostFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_produto_tipo_produto_ecm_alternative_host';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_produto_tipo_produto_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_alternative_host_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ordem' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_ecm_produto_tipo_produto_ecm_alternative_host_ecm_produto' => ['type' => 'index', 'columns' => ['ecm_produto_tipo_produto_id'], 'length' => []],
            'FK_ecm_produto_tipo_produto_ecm_alternative_host_ecm_alternative' => ['type' => 'index', 'columns' => ['ecm_alternative_host_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_produto_tipo_produto_ecm_alternative_host_ecm_alternative' => ['type' => 'foreign', 'columns' => ['ecm_alternative_host_id'], 'references' => ['ecm_alternative_host', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'FK_ecm_produto_tipo_produto_ecm_alternative_host_ecm_produto' => ['type' => 'foreign', 'columns' => ['ecm_produto_tipo_produto_id'], 'references' => ['ecm_produto_ecm_tipo_produto', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            'ecm_produto_tipo_produto_id' => 1,
            'ecm_alternative_host_id' => 1,
            'ordem' => 1
        ],
    ];
}
