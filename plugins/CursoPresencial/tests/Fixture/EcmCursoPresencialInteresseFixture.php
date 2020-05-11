<?php
namespace CursoPresencial\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EcmCursoPresencialInteresseFixture
 *
 */
class EcmCursoPresencialInteresseFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'ecm_curso_presencial_interesse';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'nome' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 80, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'telefone' => ['type' => 'string', 'length' => 15, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'ecm_curso_presencial_turma_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ecm_produto_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_ecm_curso_presencial_interesse_ecm_curso_presencial_turma' => ['type' => 'index', 'columns' => ['ecm_curso_presencial_turma_id'], 'length' => []],
            'FK_ecm_curso_presencial_interesse_ecm_produto' => ['type' => 'index', 'columns' => ['ecm_produto_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_ecm_curso_presencial_interesse_ecm_curso_presencial_turma' => ['type' => 'foreign', 'columns' => ['ecm_curso_presencial_turma_id'], 'references' => ['ecm_curso_presencial_turma', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_ecm_curso_presencial_interesse_ecm_produto' => ['type' => 'foreign', 'columns' => ['ecm_produto_id'], 'references' => ['ecm_produto', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'nome' => 'Lorem ipsum dolor sit amet',
            'email' => 'Lorem ipsum dolor sit amet',
            'telefone' => 'Lorem ipsum d',
            'ecm_curso_presencial_turma_id' => 1,
            'ecm_produto_id' => 1
        ],
    ];
}
