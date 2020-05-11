<?php
namespace Entidade\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MdlShopUserFixture
 *
 */
class MdlShopUserFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'mdl_shop_user';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'usuario' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'senha' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'cpf_cnpj' => ['type' => 'string', 'length' => 11, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'cpf_cnpj_bkp' => ['type' => 'string', 'length' => 11, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'crea' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'crea_tipo' => ['type' => 'string', 'length' => 2, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'crea_estado' => ['type' => 'string', 'length' => 2, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'nome' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'endereco' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'end_numero' => ['type' => 'integer', 'length' => 6, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'end_complemento' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'bairro' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'cidade' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'estado' => ['type' => 'string', 'length' => 2, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'cep' => ['type' => 'string', 'length' => 9, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'telefone' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'celular' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'profissao' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'entidade_principal' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'fixed' => null],
        'chave_altoqi' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'origem' => ['type' => 'string', 'length' => 15, 'null' => false, 'default' => 'QiSat', 'comment' => '', 'precision' => null, 'fixed' => null],
        'adimplente' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'data_registro' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'ecm_alternative_host_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'confirmado' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'existia_top' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'prospeccao_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'data_convenio' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'titulos' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'titulos_completo' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'registro_nacional' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'atualizado' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_mdl_shop_user_ecm_alternative_host' => ['type' => 'index', 'columns' => ['ecm_alternative_host_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_mdl_shop_user_ecm_alternative_host' => ['type' => 'foreign', 'columns' => ['ecm_alternative_host_id'], 'references' => ['ecm_alternative_host', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'usuario' => 'Lorem ipsum dolor sit amet',
            'senha' => 'Lorem ipsum dolor sit amet',
            'cpf_cnpj' => 'Lorem ips',
            'cpf_cnpj_bkp' => 'Lorem ips',
            'crea' => 'Lorem ipsum dolor ',
            'crea_tipo' => '',
            'crea_estado' => '',
            'nome' => 'Lorem ipsum dolor sit amet',
            'email' => 'Lorem ipsum dolor sit amet',
            'endereco' => 'Lorem ipsum dolor sit amet',
            'end_numero' => 1,
            'end_complemento' => 'Lorem ipsum dolor sit amet',
            'bairro' => 'Lorem ipsum dolor sit amet',
            'cidade' => 'Lorem ipsum dolor sit amet',
            'estado' => '',
            'cep' => 'Lorem i',
            'telefone' => 'Lorem ipsum d',
            'celular' => 'Lorem ipsum d',
            'profissao' => 'Lorem ipsum dolor sit amet',
            'entidade_principal' => 'Lorem ipsum dolor sit amet',
            'chave_altoqi' => 1,
            'origem' => 'Lorem ipsum d',
            'adimplente' => 1,
            'data_registro' => 1477580897,
            'ecm_alternative_host_id' => 1,
            'confirmado' => 1,
            'existia_top' => 1,
            'prospeccao_id' => 1,
            'data_convenio' => '2016-10-27',
            'titulos' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'titulos_completo' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'registro_nacional' => 'Lorem ipsum dolor sit amet',
            'atualizado' => 1
        ],
    ];
}
