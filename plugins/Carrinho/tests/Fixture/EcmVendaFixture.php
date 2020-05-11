<?php
namespace Carrinho\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmVendaFixture
 *
 */
class EcmVendaFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_venda';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'data' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'valor_parcelas' => ['type' => 'decimal', 'length' => 10, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'proposta' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'numero_parcelas' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_venda_status_id' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'mdl_user_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_operadora_pagamento_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_tipo_pagamento_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_carrinho_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_ecm_venda_ecm_carrinho' => ['type' => 'index', 'columns' => ['ecm_carrinho_id'], 'length' => []],
            'fk_ecm_venda_ecm_operadora_pagamento' => ['type' => 'index', 'columns' => ['ecm_operadora_pagamento_id'], 'length' => []],
            'fk_ecm_venda_ecm_venda_status' => ['type' => 'index', 'columns' => ['ecm_venda_status_id'], 'length' => []],
            'fk_ecm_venda_ecm_tipo_pagamento' => ['type' => 'index', 'columns' => ['ecm_tipo_pagamento_id'], 'length' => []],
            'fk_ecm_venda_mdl_user' => ['type' => 'index', 'columns' => ['mdl_user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_ecm_venda_ecm_carrinho' => ['type' => 'foreign', 'columns' => ['ecm_carrinho_id'], 'references' => ['ecm_carrinho', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'fk_ecm_venda_ecm_operadora_pagamento' => ['type' => 'foreign', 'columns' => ['ecm_operadora_pagamento_id'], 'references' => ['ecm_operadora_pagamento', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_ecm_venda_ecm_tipo_pagamento' => ['type' => 'foreign', 'columns' => ['ecm_tipo_pagamento_id'], 'references' => ['ecm_tipo_pagamento', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_ecm_venda_ecm_venda_status' => ['type' => 'foreign', 'columns' => ['ecm_venda_status_id'], 'references' => ['ecm_venda_status', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_ecm_venda_mdl_user' => ['type' => 'foreign', 'columns' => ['mdl_user_id'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'data' => '2016-06-16 10:02:05',
            'valor_parcelas' => 1.5,
            'proposta' => 1,
            'numero_parcelas' => 1,
            'ecm_venda_status_id' => 1,
            'mdl_user_id' => 1,
            'ecm_operadora_pagamento_id' => 1,
            'ecm_tipo_pagamento_id' => 1,
            'ecm_carrinho_id' => 1
        ],
    ];
}
