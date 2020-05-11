<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EcmLogContratoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EcmLogContratoTable Test Case
 */
class EcmLogContratoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Model\Table\EcmLogContratoTable     */
    public $EcmLogContrato;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ecm_log_contrato',
        'app.ecm_vendas',
        'app.mdl_user_enrolments'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmLogContrato') ? [] : ['className' => 'App\Model\Table\EcmLogContratoTable'];        $this->EcmLogContrato = TableRegistry::get('EcmLogContrato', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmLogContrato);

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
