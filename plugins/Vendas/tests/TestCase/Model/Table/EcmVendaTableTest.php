<?php
namespace Vendas\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Vendas\Model\Table\EcmVendaTable;

/**
 * Vendas\Model\Table\EcmVendaTable Test Case
 */
class EcmVendaTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Vendas\Model\Table\EcmVendaTable     */
    public $EcmVenda;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.vendas.ecm_venda',
        'plugin.vendas.ecm_venda_status',
        'plugin.vendas.mdl_user',
        'plugin.vendas.ecm_operadora_pagamento',
        'plugin.vendas.ecm_tipo_pagamento',
        'plugin.vendas.ecm_carrinho',
        'plugin.vendas.ecm_curso_presencial_email_confirmacao',
        'plugin.vendas.ecm_venda_presencial',
        'plugin.vendas.ecm_curso_presencial_turma',
        'plugin.vendas.ecm_produto',
        'plugin.vendas.ecm_tipo_produto',
        'plugin.vendas.ecm_produto_ecm_tipo_produto',
        'plugin.vendas.mdl_course',
        'plugin.vendas.mdl_enrol',
        'plugin.vendas.ecm_produto_mdl_course',
        'plugin.vendas.ecm_imagem',
        'plugin.vendas.ecm_produto_ecm_imagem',
        'plugin.vendas.ecm_produto_pacote',
        'plugin.vendas.ecm_produto_prazo_extra',
        'plugin.vendas.ecm_produto_info',
        'plugin.vendas.ecm_produto_info_arquivos',
        'plugin.vendas.ecm_produto_info_conteudo',
        'plugin.vendas.ecm_instrutor',
        'plugin.vendas.ecm_instrutor_artigo',
        'plugin.vendas.ecm_instrutor_rede_social',
        'plugin.vendas.ecm_rede_social',
        'plugin.vendas.ecm_instrutor_ecm_produto',
        'plugin.vendas.ecm_curso_presencial_data',
        'plugin.vendas.ecm_curso_presencial_local',
        'plugin.vendas.mdl_cidade',
        'plugin.vendas.mdl_estado',
        'plugin.vendas.ecm_curso_presencial_turma_ecm_instrutor',
        'plugin.vendas.ecm_transacao',
        'plugin.vendas.ecm_venda_boleto'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmVenda') ? [] : ['className' => 'Vendas\Model\Table\EcmVendaTable'];        $this->EcmVenda = TableRegistry::get('EcmVenda', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmVenda);

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
