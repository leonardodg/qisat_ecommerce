<?php
namespace Produto\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmProdutoInfoFixture
 *
 */
class EcmProdutoInfoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_produto_info';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_produto_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'titulo' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'chamada' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'persona' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'descricao' => ['type' => 'text', 'length' => 4294967295, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'qtd_aulas' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'tempo_acesso' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'tempo_aula' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'carga_horaria' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'material' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'certificado_digital' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'certificado_impresso' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'forum' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'tira_duvidas' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'mobile' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'software_demo' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'simulador' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'disponibilidade' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'metatag_titulo' => ['type' => 'string', 'length' => 150, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'metatag_key' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'metatag_descricao' => ['type' => 'string', 'length' => 512, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'url' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'produto' => ['type' => 'unique', 'columns' => ['ecm_produto_id'], 'length' => []],
            'FK_ecm_produto_info_ecm_produto' => ['type' => 'foreign', 'columns' => ['ecm_produto_id'], 'references' => ['ecm_produto', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'ecm_produto_id' => 1,
            'titulo' => 'Lorem ipsum dolor sit amet',
            'chamada' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'persona' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'descricao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'qtd_aulas' => 1,
            'tempo_acesso' => 1,
            'tempo_aula' => 1,
            'carga_horaria' => 1,
            'material' => 1,
            'certificado_digital' => 1,
            'certificado_impresso' => 1,
            'forum' => 1,
            'tira_duvidas' => 1,
            'mobile' => 1,
            'software_demo' => 1,
            'simulador' => 1,
            'disponibilidade' => 1,
            'metatag_titulo' => 'Lorem ipsum dolor sit amet',
            'metatag_key' => 'Lorem ipsum dolor sit amet',
            'metatag_descricao' => 'Lorem ipsum dolor sit amet',
            'url' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
