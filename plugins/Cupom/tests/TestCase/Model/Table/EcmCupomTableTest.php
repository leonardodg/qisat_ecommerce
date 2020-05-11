<?php
namespace Cupom\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cupom\Model\Table\EcmCupomTable;

/**
 * Cupom\Model\Table\EcmCupomTable Test Case
 */
class EcmCupomTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Cupom\Model\Table\EcmCupomTable     */
    public $EcmCupom;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.cupom.ecm_cupom',
        'plugin.cupom.ecm_alternative_host',
        'plugin.cupom.ecm_produto',
        'plugin.cupom.ecm_cupom_ecm_produto',
        'plugin.cupom.ecm_tipo_produto',
        'plugin.cupom.ecm_cupom_ecm_tipo_produto',
        'plugin.cupom.mdl_user',
        'plugin.cupom.ecm_cupom_mdl_user'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmCupom') ? [] : ['className' => 'Cupom\Model\Table\EcmCupomTable'];        $this->EcmCupom = TableRegistry::get('EcmCupom', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmCupom);

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
