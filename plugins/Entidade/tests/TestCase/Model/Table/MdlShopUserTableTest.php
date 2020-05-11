<?php
namespace Entidade\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Entidade\Model\Table\MdlShopUserTable;

/**
 * Entidade\Model\Table\MdlShopUserTable Test Case
 */
class MdlShopUserTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Entidade\Model\Table\MdlShopUserTable     */
    public $MdlShopUser;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.entidade.mdl_shop_user',
        'plugin.entidade.ecm_alternative_host',
        'plugin.entidade.ecm_carrinho',
        'plugin.entidade.ecm_cupom',
        'plugin.entidade.ecm_produto_ecm_tipo_produto',
        'plugin.entidade.ecm_produto_ecm_tipo_produto_ecm_alternative_host',
        'plugin.entidade.ecm_promocao',
        'plugin.entidade.ecm_promocao_ecm_alternative_host',
        'plugin.entidade.ecm_produto',
        'plugin.entidade.ecm_promocao_ecm_produto',
        'plugin.entidade.mdl_user',
        'plugin.entidade.ecm_grupo_permissao',
        'plugin.entidade.ecm_permissao',
        'plugin.entidade.ecm_grupo_permissao_ecm_permissao',
        'plugin.entidade.ecm_grupo_permissao_mdl_user',
        'plugin.entidade.mdl_user_ecm_alternative_host',
        'plugin.entidade.ecm_instrutor',
        'plugin.entidade.ecm_imagem',
        'plugin.entidade.ecm_instrutor_artigo',
        'plugin.entidade.ecm_instrutor_rede_social',
        'plugin.entidade.ecm_rede_social',
        'plugin.entidade.ecm_instrutor_ecm_produto',
        'plugin.entidade.mdl_user_enrolments',
        'plugin.entidade.mdl_enrol',
        'plugin.entidade.mdl_course',
        'plugin.entidade.ecm_produto_mdl_course',
        'plugin.entidade.mdl_groups_members',
        'plugin.entidade.mdl_groups',
        'plugin.entidade.mdl_user_dados',
        'plugin.entidade.mdl_user_endereco',
        'plugin.entidade.mdl_user_preferences',
        'plugin.entidade.prospeccaos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MdlShopUser') ? [] : ['className' => 'Entidade\Model\Table\MdlShopUserTable'];        $this->MdlShopUser = TableRegistry::get('MdlShopUser', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MdlShopUser);

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
