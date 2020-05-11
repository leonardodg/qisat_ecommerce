<?php
namespace Indicacao\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Indicacao\Model\Table\EcmIndicacaoCursoTable;

/**
 * Indicacao\Model\Table\EcmIndicacaoCursoTable Test Case
 */
class EcmIndicacaoCursoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Indicacao\Model\Table\EcmIndicacaoCursoTable     */
    public $EcmIndicacaoCurso;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.indicacao.ecm_indicacao_curso',
        'plugin.indicacao.mdl_user',
        'plugin.indicacao.ecm_indicacao_segmento',
        'plugin.indicacao.ecm_alternative_host'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmIndicacaoCurso') ? [] : ['className' => 'Indicacao\Model\Table\EcmIndicacaoCursoTable'];        $this->EcmIndicacaoCurso = TableRegistry::get('EcmIndicacaoCurso', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmIndicacaoCurso);

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
