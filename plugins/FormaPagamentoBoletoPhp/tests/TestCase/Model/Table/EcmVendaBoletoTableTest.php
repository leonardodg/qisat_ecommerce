<?php
namespace FormaPagamentoBoletoPhp\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use FormaPagamentoBoletoPhp\Model\Table\EcmVendaBoletoTable;

/**
 * FormaPagamentoBoletoPhp\Model\Table\EcmVendaBoletoTable Test Case
 */
class EcmVendaBoletoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \FormaPagamentoBoletoPhp\Model\Table\EcmVendaBoletoTable     */
    public $EcmVendaBoleto;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.forma_pagamento_boleto_php.ecm_venda_boleto',
        'plugin.forma_pagamento_boleto_php.ecm_venda'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmVendaBoleto') ? [] : ['className' => 'FormaPagamentoBoletoPhp\Model\Table\EcmVendaBoletoTable'];        $this->EcmVendaBoleto = TableRegistry::get('EcmVendaBoleto', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmVendaBoleto);

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
