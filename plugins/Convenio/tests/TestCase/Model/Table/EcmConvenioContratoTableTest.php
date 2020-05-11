<?php
namespace Convenio\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Convenio\Model\Table\EcmConvenioContratoTable;

/**
 * Convenio\Model\Table\EcmConvenioContratoTable Test Case
 */
class EcmConvenioContratoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Convenio\Model\Table\EcmConvenioContratoTable     */
    public $EcmConvenioContrato;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.convenio.ecm_convenio_contrato',
        'plugin.convenio.ecm_convenio',
        'plugin.convenio.ecm_convenio_tipo_instituicao',
        'plugin.convenio.mdl_cidade',
        'plugin.convenio.mdl_estado',
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
        $config = TableRegistry::exists('EcmConvenioContrato') ? [] : ['className' => 'Convenio\Model\Table\EcmConvenioContratoTable'];        $this->EcmConvenioContrato = TableRegistry::get('EcmConvenioContrato', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmConvenioContrato);

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
}
