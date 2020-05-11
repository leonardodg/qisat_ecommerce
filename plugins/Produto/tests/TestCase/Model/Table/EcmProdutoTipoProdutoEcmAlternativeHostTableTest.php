<?php
namespace Produto\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Produto\Model\Table\EcmProdutoTipoProdutoEcmAlternativeHostTable;

/**
 * Produto\Model\Table\EcmProdutoTipoProdutoEcmAlternativeHostTable Test Case
 */
class EcmProdutoTipoProdutoEcmAlternativeHostTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Produto\Model\Table\EcmProdutoTipoProdutoEcmAlternativeHostTable     */
    public $EcmProdutoTipoProdutoEcmAlternativeHost;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.produto.ecm_produto_tipo_produto_ecm_alternative_host',
        'plugin.produto.ecm_produto_ecm_tipo_produto',
        'plugin.produto.ecm_alternative_host'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmProdutoEcmTipoProdutoEcmAlternativeHost') ? [] : ['className' => 'Produto\Model\Table\EcmProdutoTipoProdutoEcmAlternativeHostTable'];        $this->EcmProdutoTipoProdutoEcmAlternativeHost = TableRegistry::get('EcmProdutoEcmTipoProdutoEcmAlternativeHost', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmProdutoTipoProdutoEcmAlternativeHost);

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
