<?php
namespace FormaPagamento\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use FormaPagamento\Model\Table\EcmOperadoraPagamentoTable;

/**
 * FormaPagamento\Model\Table\EcmOperadoraPagamentoTable Test Case
 */
class EcmOperadoraPagamentoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \FormaPagamento\Model\Table\EcmOperadoraPagamentoTable     */
    public $EcmOperadoraPagamento;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.forma_pagamento.ecm_operadora_pagamento',
        'plugin.forma_pagamento.ecm_imagem',
        'plugin.forma_pagamento.ecm_forma_pagamento',
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
        $config = TableRegistry::exists('EcmOperadoraPagamento') ? [] : ['className' => 'FormaPagamento\Model\Table\EcmOperadoraPagamentoTable'];        $this->EcmOperadoraPagamento = TableRegistry::get('EcmOperadoraPagamento', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmOperadoraPagamento);

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
