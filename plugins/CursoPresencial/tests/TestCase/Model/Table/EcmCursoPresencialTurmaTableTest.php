<?php
namespace CursoPresencial\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CursoPresencial\Model\Table\EcmCursoPresencialTurmaTable;

/**
 * CursoPresencial\Model\Table\EcmCursoPresencialTurmaTable Test Case
 */
class EcmCursoPresencialTurmaTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \CursoPresencial\Model\Table\EcmCursoPresencialTurmaTable     */
    public $EcmCursoPresencialTurma;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.curso_presencial.ecm_curso_presencial_turma',
        'plugin.curso_presencial.ecm_produto',
        'plugin.curso_presencial.ecm_curso_presencial_data',
        'plugin.curso_presencial.ecm_instrutor',
        'plugin.curso_presencial.ecm_curso_presencial_turma_ecm_instrutor'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmCursoPresencialTurma') ? [] : ['className' => 'CursoPresencial\Model\Table\EcmCursoPresencialTurmaTable'];        $this->EcmCursoPresencialTurma = TableRegistry::get('EcmCursoPresencialTurma', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmCursoPresencialTurma);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
