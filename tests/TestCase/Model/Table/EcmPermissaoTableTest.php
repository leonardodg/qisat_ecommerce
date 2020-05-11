<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EcmPermissaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EcmPermissaoTable Test Case
 */
class EcmPermissaoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Model\Table\EcmPermissaoTable     */
    public $EcmPermissao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ecm_permissao',
        'app.ecm_grupo_permissao',
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
        $config = TableRegistry::exists('EcmPermissao') ? [] : ['className' => 'App\Model\Table\EcmPermissaoTable'];        $this->EcmPermissao = TableRegistry::get('EcmPermissao', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmPermissao);

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
