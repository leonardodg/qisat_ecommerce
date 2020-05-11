<?php
namespace Repasse\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Repasse\Model\Table\EcmRepasseTable;

/**
 * Repasse\Model\Table\EcmRepasseTable Test Case
 */
class EcmRepasseTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Repasse\Model\Table\EcmRepasseTable     */
    public $EcmRepasse;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.repasse.ecm_repasse',
        'plugin.repasse.mdl_user',
        'plugin.repasse.ecm_alternative_host',
        'plugin.repasse.ecm_repasse_categorias',
        'plugin.repasse.ecm_repasse_origem'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmRepasse') ? [] : ['className' => 'Repasse\Model\Table\EcmRepasseTable'];        $this->EcmRepasse = TableRegistry::get('EcmRepasse', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmRepasse);

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
