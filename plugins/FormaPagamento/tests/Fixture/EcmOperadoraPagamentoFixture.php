<?php
namespace FormaPagamento\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmOperadoraPagamentoFixture
 *
 */
class EcmOperadoraPagamentoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_operadora_pagamento';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'nome' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'dataname' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'descricao' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'habilitado' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => 'true', 'comment' => '', 'precision' => null],
        'ecm_imagem_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_forma_pagamento_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_ecm_operadora_pagamento_ecm_forma_pagamento' => ['type' => 'index', 'columns' => ['ecm_forma_pagamento_id'], 'length' => []],
            'FK_ecm_operadora_pagamento_ecm_imagem' => ['type' => 'index', 'columns' => ['ecm_imagem_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'nome' => ['type' => 'unique', 'columns' => ['nome'], 'length' => []],
            'FK_ecm_operadora_pagamento_ecm_forma_pagamento' => ['type' => 'foreign', 'columns' => ['ecm_forma_pagamento_id'], 'references' => ['ecm_forma_pagamento', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_operadora_pagamento_ecm_imagem' => ['type' => 'foreign', 'columns' => ['ecm_imagem_id'], 'references' => ['ecm_imagem', 'id'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
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
            'nome' => 'Lorem ipsum dolor sit amet',
            'dataname' => 'Lorem ipsum dolor sit amet',
            'descricao' => 'Lorem ipsum dolor sit amet',
            'habilitado' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'ecm_imagem_id' => 1,
            'ecm_forma_pagamento_id' => 1
        ],
    ];
}
