<?php
namespace Entidade\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Entidade\Model\Table\MdlUserEcmAlternativeHostTable;

/**
 * Entidade\Model\Table\MdlUserEcmAlternativeHostTable Test Case
 */
class MdlUserEcmAlternativeHostTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Entidade\Model\Table\MdlUserEcmAlternativeHostTable     */
    public $MdlUserEcmAlternativeHost;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.entidade.mdl_user_ecm_alternative_host',
        'plugin.entidade.mdl_user',
        'plugin.entidade.ecm_alternative_host',
        'plugin.entidade.ecm_carrinho',
        'plugin.entidade.ecm_cupom',
        'plugin.entidade.ecm_produto_ecm_tipo_produto',
        'plugin.entidade.ecm_produto_ecm_tipo_produto_ecm_alternative_host',
        'plugin.entidade.ecm_promocao',
        'plugin.entidade.ecm_promocao_ecm_alternative_host',
        'plugin.entidade.ecm_produto',
        'plugin.entidade.ecm_promocao_ecm_produto'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MdlUserEcmAlternativeHost') ? [] : ['className' => 'Entidade\Model\Table\MdlUserEcmAlternativeHostTable'];        $this->MdlUserEcmAlternativeHost = TableRegistry::get('MdlUserEcmAlternativeHost', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MdlUserEcmAlternativeHost);

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
