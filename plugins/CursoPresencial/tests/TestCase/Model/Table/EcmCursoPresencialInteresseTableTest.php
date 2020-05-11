<?php
namespace CursoPresencial\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CursoPresencial\Model\Table\EcmCursoPresencialInteresseTable;

/**
 * CursoPresencial\Model\Table\EcmCursoPresencialInteresseTable Test Case
 */
class EcmCursoPresencialInteresseTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \CursoPresencial\Model\Table\EcmCursoPresencialInteresseTable     */
    public $EcmCursoPresencialInteresse;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.curso_presencial.ecm_curso_presencial_interesse',
        'plugin.curso_presencial.ecm_curso_presencial_turma',
        'plugin.curso_presencial.ecm_produto',
        'plugin.curso_presencial.ecm_tipo_produto',
        'plugin.curso_presencial.ecm_produto_ecm_tipo_produto',
        'plugin.curso_presencial.mdl_course',
        'plugin.curso_presencial.mdl_enrol',
        'plugin.curso_presencial.ecm_produto_mdl_course',
        'plugin.curso_presencial.ecm_imagem',
        'plugin.curso_presencial.ecm_produto_ecm_imagem',
        'plugin.curso_presencial.ecm_produto_pacote',
        'plugin.curso_presencial.ecm_produto_prazo_extra',
        'plugin.curso_presencial.ecm_produto_info',
        'plugin.curso_presencial.ecm_produto_info_arquivos',
        'plugin.curso_presencial.ecm_produto_info_conteudo',
        'plugin.curso_presencial.ecm_instrutor',
        'plugin.curso_presencial.mdl_user',
        'plugin.curso_presencial.ecm_instrutor_artigo',
        'plugin.curso_presencial.ecm_instrutor_rede_social',
        'plugin.curso_presencial.ecm_rede_social',
        'plugin.curso_presencial.ecm_instrutor_ecm_produto',
        'plugin.curso_presencial.ecm_curso_presencial_data',
        'plugin.curso_presencial.ecm_curso_presencial_local',
        'plugin.curso_presencial.mdl_cidade',
        'plugin.curso_presencial.mdl_estado',
        'plugin.curso_presencial.ecm_curso_presencial_turma_ecm_instrutor'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmCursoPresencialInteresse') ? [] : ['className' => 'CursoPresencial\Model\Table\EcmCursoPresencialInteresseTable'];        $this->EcmCursoPresencialInteresse = TableRegistry::get('EcmCursoPresencialInteresse', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmCursoPresencialInteresse);

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
