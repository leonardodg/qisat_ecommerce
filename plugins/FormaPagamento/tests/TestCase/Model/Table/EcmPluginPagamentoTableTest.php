<?php
namespace FormaPagamento\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use FormaPagamento\Model\Table\EcmPluginPagamentoTable;

/**
 * FormaPagamento\Model\Table\EcmPluginPagamentoTable Test Case
 */
class EcmPluginPagamentoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \FormaPagamento\Model\Table\EcmPluginPagamentoTable     */
    public $EcmPluginPagamento;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.forma_pagamento.ecm_plugin_pagamento',
        'plugin.forma_pagamento.ecm_forma_pagamento',
        'plugin.forma_pagamento.ecm_operadora_pagamento',
        'plugin.forma_pagamento.ecm_imagem',
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
        $config = TableRegistry::exists('EcmPluginPagamento') ? [] : ['className' => 'FormaPagamento\Model\Table\EcmPluginPagamentoTable'];        $this->EcmPluginPagamento = TableRegistry::get('EcmPluginPagamento', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmPluginPagamento);

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
