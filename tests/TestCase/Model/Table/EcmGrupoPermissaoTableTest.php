<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EcmGrupoPermissaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EcmGrupoPermissaoTable Test Case
 */
class EcmGrupoPermissaoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Model\Table\EcmGrupoPermissaoTable     */
    public $EcmGrupoPermissao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ecm_grupo_permissao',
        'app.ecm_permissao',
        'app.ecm_grupo_permissao_ecm_permissao',
        'app.mdl_user',
        'app.ecm_grupo_permissao_mdl_user'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmGrupoPermissao') ? [] : ['className' => 'App\Model\Table\EcmGrupoPermissaoTable'];        $this->EcmGrupoPermissao = TableRegistry::get('EcmGrupoPermissao', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmGrupoPermissao);

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
