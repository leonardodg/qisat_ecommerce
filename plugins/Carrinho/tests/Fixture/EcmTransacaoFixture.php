<?php
namespace Carrinho\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmTransacaoFixture
 *
 */
class EcmTransacaoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_transacao';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'id_integracao' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_transacao_status_id' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'capturar' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => 'true', 'comment' => '', 'precision' => null],
        'mdl_user_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'valor' => ['type' => 'decimal', 'length' => 10, 'precision' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'descricao' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'ecm_tipo_pagamento_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_operadora_pagamento_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_venda_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'data_envio' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'data_retorno' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'tid' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'nsu' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'pan' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'arp' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'lr' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'url' => ['type' => 'string', 'length' => 150, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'erro' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'teste' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => 'false', 'comment' => '', 'precision' => null],
        'ip' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'FK_ecm_transacao_ecm_operadora_pagamento' => ['type' => 'index', 'columns' => ['ecm_operadora_pagamento_id'], 'length' => []],
            'FK_ecm_transacao_ecm_tipo_pagamento' => ['type' => 'index', 'columns' => ['ecm_tipo_pagamento_id'], 'length' => []],
            'FK_ecm_transacao_mdl_user' => ['type' => 'index', 'columns' => ['mdl_user_id'], 'length' => []],
            'FK_ecm_transacao_ecm_venda' => ['type' => 'index', 'columns' => ['ecm_venda_id'], 'length' => []],
            'FK_ecm_transacao_ecm_transacao_status' => ['type' => 'index', 'columns' => ['ecm_transacao_status_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_transacao_ecm_operadora_pagamento' => ['type' => 'foreign', 'columns' => ['ecm_operadora_pagamento_id'], 'references' => ['ecm_operadora_pagamento', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_transacao_ecm_tipo_pagamento' => ['type' => 'foreign', 'columns' => ['ecm_tipo_pagamento_id'], 'references' => ['ecm_tipo_pagamento', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_transacao_ecm_transacao_status' => ['type' => 'foreign', 'columns' => ['ecm_transacao_status_id'], 'references' => ['ecm_transacao_status', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_transacao_ecm_venda' => ['type' => 'foreign', 'columns' => ['ecm_venda_id'], 'references' => ['ecm_venda', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_transacao_mdl_user' => ['type' => 'foreign', 'columns' => ['mdl_user_id'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'id_integracao' => 1,
            'ecm_transacao_status_id' => 1,
            'capturar' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'mdl_user_id' => 1,
            'valor' => 1.5,
            'descricao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'ecm_tipo_pagamento_id' => 1,
            'ecm_operadora_pagamento_id' => 1,
            'ecm_venda_id' => 1,
            'data_envio' => '2016-06-17 08:02:29',
            'data_retorno' => '2016-06-17 08:02:29',
            'tid' => 'Lorem ipsum dolor sit amet',
            'nsu' => 'Lorem ipsum dolor sit amet',
            'pan' => 'Lorem ipsum dolor sit amet',
            'arp' => 'Lorem ipsum dolor sit amet',
            'lr' => 'Lorem ipsum dolor sit amet',
            'url' => 'Lorem ipsum dolor sit amet',
            'erro' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'teste' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'ip' => 'Lorem ipsum d'
        ],
    ];
}
