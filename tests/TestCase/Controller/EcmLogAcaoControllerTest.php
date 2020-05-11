<?php
namespace App\Test\TestCase\Controller;

use App\Controller\EcmLogAcaoController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\EcmLogAcaoController Test Case
 */
class EcmLogAcaoControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ecm_log_acao',
        'app.mdl_user',
        'app.ecm_grupo_permissao',
        'app.ecm_permissao',
        'app.ecm_grupo_permissao_ecm_permissao',
        'app.ecm_grupo_permissao_mdl_user',
        'app.ecm_alternative_host',
        'app.ecm_carrinho',
        'app.ecm_cupom',
        'app.ecm_produto_ecm_tipo_produto',
        'app.ecm_produto_ecm_tipo_produto_ecm_alternative_host',
        'app.ecm_promocao',
        'app.ecm_promocao_ecm_alternative_host',
        'app.mdl_user_ecm_alternative_host',
        'app.mdl_user_endereco',
        'app.ecm_instrutor',
        'app.ecm_imagem',
        'app.ecm_instrutor_artigo',
        'app.ecm_instrutor_rede_social',
        'app.ecm_rede_social',
        'app.ecm_instrutor_ecm_produto',
        'app.ecm_produto',
        'app.ecm_tipo_produto',
        'app.mdl_course',
        'app.mdl_enrol',
        'app.ecm_produto_mdl_course',
        'app.ecm_produto_ecm_imagem',
        'app.ecm_curso_presencial_turma',
        'app.ecm_curso_presencial_data',
        'app.ecm_curso_presencial_local',
        'app.mdl_cidade',
        'app.mdl_estado',
        'app.ecm_curso_presencial_turma_ecm_instrutor',
        'app.ecm_produto_pacote',
        'app.ecm_produto_prazo_extra',
        'app.ecm_produto_info',
        'app.ecm_produto_info_arquivos',
        'app.ecm_produto_info_conteudo',
        'app.mdl_user_enrolments',
        'app.mdl_groups_members',
        'app.mdl_groups'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
