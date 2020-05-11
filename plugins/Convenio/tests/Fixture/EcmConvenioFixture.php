<?php
namespace Convenio\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmConvenioFixture
 *
 */
class EcmConvenioFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_convenio';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_convenio_tipo_instituicao_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_convenio_contrato_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'mdl_cidade_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'nome_responsavel' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'nome_coordenador' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'nome_instituicao' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'curso' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'disciplina' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'cargo' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'telefone' => ['type' => 'string', 'length' => 15, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'logo' => ['type' => 'string', 'length' => 150, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'data_registro' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'FK_ecm_convenio_ecm_convenio_tipo_instituicao' => ['type' => 'index', 'columns' => ['ecm_convenio_tipo_instituicao_id'], 'length' => []],
            'FK_ecm_convenio_ecm_convenio_contrato' => ['type' => 'index', 'columns' => ['ecm_convenio_contrato_id'], 'length' => []],
            'FK_ecm_convenio_mdl_cidade' => ['type' => 'index', 'columns' => ['mdl_cidade_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_convenio_ecm_convenio_contrato' => ['type' => 'foreign', 'columns' => ['ecm_convenio_contrato_id'], 'references' => ['ecm_convenio_contrato', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_ecm_convenio_ecm_convenio_tipo_instituicao' => ['type' => 'foreign', 'columns' => ['ecm_convenio_tipo_instituicao_id'], 'references' => ['ecm_convenio_tipo_instituicao', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_ecm_convenio_mdl_cidade' => ['type' => 'foreign', 'columns' => ['mdl_cidade_id'], 'references' => ['mdl_cidade', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'ecm_convenio_tipo_instituicao_id' => 1,
            'ecm_convenio_contrato_id' => 1,
            'mdl_cidade_id' => 1,
            'nome_responsavel' => 'Lorem ipsum dolor sit amet',
            'nome_coordenador' => 'Lorem ipsum dolor sit amet',
            'nome_instituicao' => 'Lorem ipsum dolor sit amet',
            'curso' => 'Lorem ipsum dolor sit amet',
            'disciplina' => 'Lorem ipsum dolor sit amet',
            'cargo' => 'Lorem ipsum dolor sit amet',
            'email' => 'Lorem ipsum dolor sit amet',
            'telefone' => 'Lorem ipsum d',
            'logo' => 'Lorem ipsum dolor sit amet',
            'data_registro' => '2016-09-13 09:29:12'
        ],
    ];
}
