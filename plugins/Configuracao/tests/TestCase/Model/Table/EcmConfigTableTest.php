<?php
namespace Configuracao\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Configuracao\Model\Table\EcmConfigTable;

/**
 * Configuracao\Model\Table\EcmConfigTable Test Case
 */
class EcmConfigTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Configuracao\Model\Table\EcmConfigTable     */
    public $EcmConfig;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.configuracao.ecm_config'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmConfig') ? [] : ['className' => 'Configuracao\Model\Table\EcmConfigTable'];        $this->EcmConfig = TableRegistry::get('EcmConfig', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmConfig);

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
