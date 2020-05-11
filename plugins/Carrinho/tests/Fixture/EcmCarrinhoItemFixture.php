<?php
namespace Carrinho\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmCarrinhoItemFixture
 *
 */
class EcmCarrinhoItemFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_carrinho_item';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_carrinho_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_produto_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'valor_produto' => ['type' => 'decimal', 'length' => 10, 'precision' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'quantidade' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'status' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'ecm_curso_presencial_turma_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'valor_produto_desconto' => ['type' => 'decimal', 'length' => 10, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'ecm_promocao_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_ecm_carrinho_item_ecm_carrinho' => ['type' => 'index', 'columns' => ['ecm_carrinho_id'], 'length' => []],
            'FK_ecm_carrinho_item_ecm_produto' => ['type' => 'index', 'columns' => ['ecm_produto_id'], 'length' => []],
            'FK_ecm_carrinho_item_ecm_curso_presencial_turma' => ['type' => 'index', 'columns' => ['ecm_curso_presencial_turma_id'], 'length' => []],
            'FK_ecm_carrinho_item_ecm_promocao' => ['type' => 'index', 'columns' => ['ecm_promocao_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_carrinho_item_ecm_carrinho' => ['type' => 'foreign', 'columns' => ['ecm_carrinho_id'], 'references' => ['ecm_carrinho', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_carrinho_item_ecm_curso_presencial_turma' => ['type' => 'foreign', 'columns' => ['ecm_curso_presencial_turma_id'], 'references' => ['ecm_curso_presencial_turma', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_carrinho_item_ecm_produto' => ['type' => 'foreign', 'columns' => ['ecm_produto_id'], 'references' => ['ecm_produto', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_carrinho_item_ecm_promocao' => ['type' => 'foreign', 'columns' => ['ecm_promocao_id'], 'references' => ['ecm_promocao', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'ecm_carrinho_id' => 1,
            'ecm_produto_id' => 1,
            'valor_produto' => 1.5,
            'quantidade' => 1,
            'status' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'ecm_curso_presencial_turma_id' => 1,
            'valor_produto_desconto' => 1.5,
            'ecm_promocao_id' => 1
        ],
    ];
}
