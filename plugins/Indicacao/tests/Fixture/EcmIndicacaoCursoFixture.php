<?php
namespace Indicacao\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmIndicacaoCursoFixture
 *
 */
class EcmIndicacaoCursoFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_indicacao_curso';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'mdl_user_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_indicacao_segmento_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'tema' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'timemodified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'ecm_alternative_host_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'nome_base_antiga' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'FK_ecm_indicacao_curso_mdl_user' => ['type' => 'index', 'columns' => ['mdl_user_id'], 'length' => []],
            'FK_ecm_indicacao_curso_ecm_indicacao_segmento' => ['type' => 'index', 'columns' => ['ecm_indicacao_segmento_id'], 'length' => []],
            'FK_ecm_indicacao_curso_ecm_alternative_host' => ['type' => 'index', 'columns' => ['ecm_alternative_host_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_indicacao_curso_ecm_alternative_host' => ['type' => 'foreign', 'columns' => ['ecm_alternative_host_id'], 'references' => ['ecm_alternative_host', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_indicacao_curso_ecm_indicacao_segmento' => ['type' => 'foreign', 'columns' => ['ecm_indicacao_segmento_id'], 'references' => ['ecm_indicacao_segmento', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_indicacao_curso_mdl_user' => ['type' => 'foreign', 'columns' => ['mdl_user_id'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'mdl_user_id' => 1,
            'ecm_indicacao_segmento_id' => 1,
            'tema' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'timemodified' => '2016-11-09 12:17:15',
            'ecm_alternative_host_id' => 1,
            'nome_base_antiga' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
