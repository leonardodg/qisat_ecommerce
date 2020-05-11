<?php
namespace CursoPresencial\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CursoPresencial\Model\Table\EcmCursoPresencialLocalTable;

/**
 * CursoPresencial\Model\Table\EcmCursoPresencialLocalTable Test Case
 */
class EcmCursoPresencialLocalTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \CursoPresencial\Model\Table\EcmCursoPresencialLocalTable     */
    public $EcmCursoPresencialLocal;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.curso_presencial.ecm_curso_presencial_local',
        'plugin.curso_presencial.mdl_cidade'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmCursoPresencialLocal') ? [] : ['className' => 'CursoPresencial\Model\Table\EcmCursoPresencialLocalTable'];        $this->EcmCursoPresencialLocal = TableRegistry::get('EcmCursoPresencialLocal', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmCursoPresencialLocal);

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
