<?php
namespace Produto\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Produto\Model\Table\MdlFaseTable;

/**
 * Produto\Model\Table\MdlFaseTable Test Case
 */
class MdlFaseTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Produto\Model\Table\MdlFaseTable     */
    public $MdlFase;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.produto.mdl_fase',
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
        'plugin.produto.ecm_instrutor_artigo',
        'plugin.produto.ecm_instrutor_rede_social',
        'plugin.produto.ecm_rede_social',
        'plugin.produto.ecm_instrutor_ecm_produto',
        'plugin.produto.ecm_instrutor_area',
        'plugin.produto.ecm_instrutor_ecm_instrutor_area',
        'plugin.produto.ecm_curso_presencial_turma_ecm_instrutor',
        'plugin.produto.ecm_produto_pacote',
        'plugin.produto.ecm_produto_prazo_extra',
        'plugin.produto.ecm_produto_info',
        'plugin.produto.ecm_produto_info_conteudo',
        'plugin.produto.ecm_produto_info_faq',
        'plugin.produto.ecm_produto_info_arquivos',
        'plugin.produto.ecm_produto_info_arquivos_tipos',
        'plugin.produto.ecm_promocao',
        'plugin.produto.ecm_alternative_host',
        'plugin.produto.ecm_carrinho',
        'plugin.produto.ecm_cupom',
        'plugin.produto.ecm_promocao_ecm_alternative_host',
        'plugin.produto.mdl_user_ecm_alternative_host',
        'plugin.produto.ecm_alternative_host_ecm_imagem',
        'plugin.produto.ecm_produto_ecm_tipo_produto_ecm_alternative_host',
        'plugin.produto.ecm_promocao_ecm_produto',
        'plugin.produto.mdl_groups',
        'plugin.produto.mdl_course_mdl_fase'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MdlFase') ? [] : ['className' => 'Produto\Model\Table\MdlFaseTable'];        $this->MdlFase = TableRegistry::get('MdlFase', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MdlFase);

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
