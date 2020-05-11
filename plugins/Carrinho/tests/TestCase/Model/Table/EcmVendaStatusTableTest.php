<?php
namespace Carrinho\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Carrinho\Model\Table\EcmVendaStatusTable;

/**
 * Carrinho\Model\Table\EcmVendaStatusTable Test Case
 */
class EcmVendaStatusTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Carrinho\Model\Table\EcmVendaStatusTable     */
    public $EcmVendaStatus;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.carrinho.ecm_venda_status',
        'plugin.carrinho.ecm_venda',
        'plugin.carrinho.mdl_users',
        'plugin.carrinho.ecm_operadora_pagamento',
        'plugin.carrinho.ecm_tipo_pagamento',
        'plugin.carrinho.ecm_carrinho',
        'plugin.carrinho.mdl_user',
        'plugin.carrinho.ecm_grupo_permissao',
        'plugin.carrinho.ecm_permissao',
        'plugin.carrinho.ecm_grupo_permissao_ecm_permissao',
        'plugin.carrinho.ecm_grupo_permissao_mdl_user',
        'plugin.carrinho.ecm_alternative_host',
        'plugin.carrinho.ecm_cupom',
        'plugin.carrinho.ecm_produto_ecm_tipo_produto',
        'plugin.carrinho.ecm_produto_ecm_tipo_produto_ecm_alternative_host',
        'plugin.carrinho.ecm_promocao',
        'plugin.carrinho.ecm_promocao_ecm_alternative_host',
        'plugin.carrinho.mdl_user_ecm_alternative_host',
        'plugin.carrinho.ecm_carrinho_item',
        'plugin.carrinho.ecm_produto',
        'plugin.carrinho.ecm_tipo_produto',
        'plugin.carrinho.mdl_course',
        'plugin.carrinho.ecm_produto_mdl_course',
        'plugin.carrinho.ecm_imagem',
        'plugin.carrinho.ecm_produto_ecm_imagem',
        'plugin.carrinho.ecm_curso_presencial_turma',
        'plugin.carrinho.ecm_curso_presencial_data',
        'plugin.carrinho.ecm_curso_presencial_local',
        'plugin.carrinho.mdl_cidade',
        'plugin.carrinho.mdl_estado',
        'plugin.carrinho.ecm_instrutor',
        'plugin.carrinho.ecm_curso_presencial_turma_ecm_instrutor',
        'plugin.carrinho.ecm_transacao'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmVendaStatus') ? [] : ['className' => 'Carrinho\Model\Table\EcmVendaStatusTable'];        $this->EcmVendaStatus = TableRegistry::get('EcmVendaStatus', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmVendaStatus);

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
