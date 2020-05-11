<?php
namespace Produto\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Produto\Model\Table\EcmProdutoInfoTable;

/**
 * Produto\Model\Table\EcmProdutoInfoTable Test Case
 */
class EcmProdutoInfoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Produto\Model\Table\EcmProdutoInfoTable     */
    public $EcmProdutoInfo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.produto.ecm_produto_info',
        'plugin.produto.ecm_produto',
        'plugin.produto.ecm_tipo_produto',
        'plugin.produto.ecm_produto_ecm_tipo_produto',
        'plugin.produto.mdl_course',
        'plugin.produto.mdl_enrol',
        'plugin.produto.ecm_produto_mdl_course',
        'plugin.produto.ecm_imagem',
        'plugin.produto.ecm_produto_ecm_imagem',
        'plugin.produto.ecm_curso_presencial_turma',
        'plugin.produto.ecm_curso_presencial_data',
        'plugin.produto.ecm_curso_presencial_local',
        'plugin.produto.mdl_cidade',
        'plugin.produto.mdl_estado',
        'plugin.produto.ecm_instrutor',
        'plugin.produto.mdl_user',
        'plugin.produto.ecm_curso_presencial_turma_ecm_instrutor',
        'plugin.produto.ecm_produto_pacote',
        'plugin.produto.ecm_produto_prazo_extra',
        'plugin.produto.ecm_produto_info_arquivos',
        'plugin.produto.ecm_produto_info_conteudo'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmProdutoInfo') ? [] : ['className' => 'Produto\Model\Table\EcmProdutoInfoTable'];        $this->EcmProdutoInfo = TableRegistry::get('EcmProdutoInfo', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmProdutoInfo);

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
