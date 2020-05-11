<?php
namespace FormaPagamento\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use FormaPagamento\Model\Table\EcmFormaPagamentoTable;

/**
 * FormaPagamento\Model\Table\EcmFormaPagamentoTable Test Case
 */
class EcmFormaPagamentoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \FormaPagamento\Model\Table\EcmFormaPagamentoTable     */
    public $EcmFormaPagamento;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.forma_pagamento.ecm_forma_pagamento',
        'plugin.forma_pagamento.ecm_operadora_pagamento',
        'plugin.forma_pagamento.ecm_tipo_pagamento'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmFormaPagamento') ? [] : ['className' => 'FormaPagamento\Model\Table\EcmFormaPagamentoTable'];        $this->EcmFormaPagamento = TableRegistry::get('EcmFormaPagamento', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmFormaPagamento);

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
