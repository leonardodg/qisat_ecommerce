<?php
namespace Produto\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Produto\Model\Table\EcmProdutoTable;

/**
 * Produto\Model\Table\EcmProdutoTable Test Case
 */
class EcmProdutoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Produto\Model\Table\EcmProdutoTable     */
    public $EcmProduto;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.produto.ecm_produto',
        'plugin.produto.ecm_imagem',
        'plugin.produto.ecm_produto_ecm_imagem',
        'plugin.produto.ecm_tipo_produto',
        'plugin.produto.ecm_produto_ecm_tipo_produto',
        'plugin.produto.mdl_course',
        'plugin.produto.ecm_produto_mdl_course'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmProduto') ? [] : ['className' => 'Produto\Model\Table\EcmProdutoTable'];        $this->EcmProduto = TableRegistry::get('EcmProduto', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmProduto);

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
