<?php
namespace Vendas\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Vendas\Model\Table\EcmCursoPresencialEmailConfirmacaoTable;

/**
 * Vendas\Model\Table\EcmCursoPresencialEmailConfirmacaoTable Test Case
 */
class EcmCursoPresencialEmailConfirmacaoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Vendas\Model\Table\EcmCursoPresencialEmailConfirmacaoTable     */
    public $EcmCursoPresencialEmailConfirmacao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        'plugin.vendas.ecm_curso_presencial_data',
        'plugin.vendas.ecm_curso_presencial_local',
        'plugin.vendas.mdl_cidade',
        'plugin.vendas.mdl_estado',
        'plugin.vendas.ecm_instrutor',
        'plugin.vendas.mdl_user',
        'plugin.vendas.ecm_curso_presencial_turma_ecm_instrutor',
        'plugin.vendas.ecm_venda'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmCursoPresencialEmailConfirmacao') ? [] : ['className' => 'Vendas\Model\Table\EcmCursoPresencialEmailConfirmacaoTable'];        $this->EcmCursoPresencialEmailConfirmacao = TableRegistry::get('EcmCursoPresencialEmailConfirmacao', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmCursoPresencialEmailConfirmacao);

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
