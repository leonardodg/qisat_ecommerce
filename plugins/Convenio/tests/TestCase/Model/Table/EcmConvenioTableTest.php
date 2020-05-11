<?php
namespace Convenio\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Convenio\Model\Table\EcmConvenioTable;

/**
 * Convenio\Model\Table\EcmConvenioTable Test Case
 */
class EcmConvenioTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Convenio\Model\Table\EcmConvenioTable     */
    public $EcmConvenio;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.convenio.ecm_convenio',
        'plugin.convenio.ecm_convenio_tipo_instituicao',
        'plugin.convenio.ecm_convenio_contrato',
        'plugin.convenio.mdl_cidade',
        'plugin.convenio.ecm_convenio_interesse'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmConvenio') ? [] : ['className' => 'Convenio\Model\Table\EcmConvenioTable'];        $this->EcmConvenio = TableRegistry::get('EcmConvenio', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmConvenio);

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
