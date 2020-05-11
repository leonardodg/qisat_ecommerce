<?php
namespace DuvidasFrequentes\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use DuvidasFrequentes\Model\Table\EcmDuvidasFrequentesTable;

/**
 * DuvidasFrequentes\Model\Table\EcmDuvidasFrequentesTable Test Case
 */
class EcmDuvidasFrequentesTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \DuvidasFrequentes\Model\Table\EcmDuvidasFrequentesTable     */
    public $EcmDuvidasFrequentes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.duvidas_frequentes.ecm_duvidas_frequentes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmDuvidasFrequentes') ? [] : ['className' => 'DuvidasFrequentes\Model\Table\EcmDuvidasFrequentesTable'];        $this->EcmDuvidasFrequentes = TableRegistry::get('EcmDuvidasFrequentes', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmDuvidasFrequentes);

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
