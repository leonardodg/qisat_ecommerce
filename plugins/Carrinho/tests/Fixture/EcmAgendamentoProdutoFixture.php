<?php
namespace Carrinho\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmAgendamentoProdutoFixture
 *
 */
class EcmAgendamentoProdutoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_agendamento_produto';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_carrinho_item_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'datainicio' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'duracao' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'pedido' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'FK_ecm_agendamento_produto_ecm_carrinho_item' => ['type' => 'index', 'columns' => ['ecm_carrinho_item_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_agendamento_produto_ecm_carrinho_item' => ['type' => 'foreign', 'columns' => ['ecm_carrinho_item_id'], 'references' => ['ecm_carrinho_item', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'ecm_carrinho_item_id' => 1,
            'datainicio' => '2016-07-06 11:44:00',
            'duracao' => 1,
            'pedido' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
