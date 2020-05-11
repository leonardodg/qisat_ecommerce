<?php
namespace Vendas\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmCursoPresencialEmailConfirmacaoFixture
 *
 */
class EcmCursoPresencialEmailConfirmacaoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_curso_presencial_email_confirmacao';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_venda_presencial_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_venda_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'enviado' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'data_envio' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'FK_ecm_curso_presencial_email_confirmacao_ecm_venda_presencial' => ['type' => 'index', 'columns' => ['ecm_venda_presencial_id'], 'length' => []],
            'FK_ecm_curso_presencial_email_confirmacao_ecm_venda' => ['type' => 'index', 'columns' => ['ecm_venda_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_curso_presencial_email_confirmacao_ecm_venda' => ['type' => 'foreign', 'columns' => ['ecm_venda_id'], 'references' => ['ecm_venda', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_curso_presencial_email_confirmacao_ecm_venda_presencial' => ['type' => 'foreign', 'columns' => ['ecm_venda_presencial_id'], 'references' => ['ecm_venda_presencial', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'ecm_venda_presencial_id' => 1,
            'ecm_venda_id' => 1,
            'enviado' => 1,
            'data_envio' => '2016-07-01 10:14:20'
        ],
    ];
}
