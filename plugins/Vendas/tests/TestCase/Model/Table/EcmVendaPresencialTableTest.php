<?php
namespace Vendas\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Vendas\Model\Table\EcmVendaPresencialTable;

/**
 * Vendas\Model\Table\EcmVendaPresencialTable Test Case
 */
class EcmVendaPresencialTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Vendas\Model\Table\EcmVendaPresencialTable     */
    public $EcmVendaPresencial;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.vendas.ecm_venda_presencial',
        'plugin.vendas.ecm_curso_presencial_turma',
        'plugin.vendas.mdl_user'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmVendaPresencial') ? [] : ['className' => 'Vendas\Model\Table\EcmVendaPresencialTable'];        $this->EcmVendaPresencial = TableRegistry::get('EcmVendaPresencial', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmVendaPresencial);

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
