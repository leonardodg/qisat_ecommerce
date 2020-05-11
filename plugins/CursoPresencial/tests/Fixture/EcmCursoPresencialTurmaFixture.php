<?php
namespace CursoPresencial\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmCursoPresencialTurmaFixture
 *
 */
class EcmCursoPresencialTurmaFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_curso_presencial_turma';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ecm_produto_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'carga_horaria' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'vagas_total' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => '15', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'vagas_preenchidas' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'valor' => ['type' => 'decimal', 'length' => 10, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'valor_produto' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => 'true', 'comment' => '', 'precision' => null],
        'status' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => 'Ativo', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_ecm_curso_presencial_turma_ecm_produto' => ['type' => 'index', 'columns' => ['ecm_produto_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_ecm_curso_presencial_turma_ecm_produto' => ['type' => 'foreign', 'columns' => ['ecm_produto_id'], 'references' => ['ecm_produto', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            'carga_horaria' => 1,
            'vagas_total' => 1,
            'vagas_preenchidas' => 1,
            'valor' => 1.5,
            'valor_produto' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'status' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
        ],
    ];
}
