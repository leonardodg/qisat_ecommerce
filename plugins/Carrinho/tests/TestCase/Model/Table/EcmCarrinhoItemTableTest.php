<?php
namespace Carrinho\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Carrinho\Model\Table\EcmCarrinhoItemTable;

/**
 * Carrinho\Model\Table\EcmCarrinhoItemTable Test Case
 */
class EcmCarrinhoItemTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Carrinho\Model\Table\EcmCarrinhoItemTable     */
    public $EcmCarrinhoItem;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.carrinho.ecm_carrinho_item',
        'plugin.carrinho.ecm_carrinho',
        'plugin.carrinho.mdl_user',
        'plugin.carrinho.ecm_cupom',
        'plugin.carrinho.ecm_alternative_host',
        'plugin.carrinho.ecm_produto',
        'plugin.carrinho.ecm_cupom_ecm_produto',
        'plugin.carrinho.ecm_tipo_produto',
        'plugin.carrinho.ecm_cupom_ecm_tipo_produto',
        'plugin.carrinho.ecm_cupom_mdl_user',
        'plugin.carrinho.ecm_promocao',
        'plugin.carrinho.ecm_promocao_ecm_alternative_host',
        'plugin.carrinho.ecm_promocao_ecm_produto',
        'plugin.carrinho.ecm_promocao_ecm_carrinho',
        'plugin.carrinho.ecm_curso_presencial_turma'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmCarrinhoItem') ? [] : ['className' => 'Carrinho\Model\Table\EcmCarrinhoItemTable'];        $this->EcmCarrinhoItem = TableRegistry::get('EcmCarrinhoItem', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmCarrinhoItem);

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
