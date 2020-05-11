<?php
namespace FormaPagamento\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use FormaPagamento\Model\Table\EcmTipoPagamentoTable;

/**
 * FormaPagamento\Model\Table\EcmTipoPagamentoTable Test Case
 */
class EcmTipoPagamentoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \FormaPagamento\Model\Table\EcmTipoPagamentoTable     */
    public $EcmTipoPagamento;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.forma_pagamento.ecm_tipo_pagamento',
        'plugin.forma_pagamento.ecm_forma_pagamento',
        'plugin.forma_pagamento.ecm_operadora_pagamento'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmTipoPagamento') ? [] : ['className' => 'FormaPagamento\Model\Table\EcmTipoPagamentoTable'];        $this->EcmTipoPagamento = TableRegistry::get('EcmTipoPagamento', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmTipoPagamento);

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
