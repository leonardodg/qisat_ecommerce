<?php
namespace Instrutor\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmInstrutorFixture
 *
 */
class EcmInstrutorFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_instrutor';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'mdl_user_id' => ['type' => 'biginteger', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'descricao' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'ecm_imagem_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_ecm_instrutor_ecm_imagem' => ['type' => 'index', 'columns' => ['ecm_imagem_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'ecm_user_id' => ['type' => 'unique', 'columns' => ['mdl_user_id'], 'length' => []],
            'FK_ecm_instrutor_ecm_imagem' => ['type' => 'foreign', 'columns' => ['ecm_imagem_id'], 'references' => ['ecm_imagem', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_instrutor_mdl_user' => ['type' => 'foreign', 'columns' => ['mdl_user_id'], 'references' => ['mdl_user', 'id'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
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
            'mdl_user_id' => 1,
            'descricao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'ecm_imagem_id' => 1
        ],
    ];
}
