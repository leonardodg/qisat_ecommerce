<?php
namespace Repasse\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Repasse\Model\Table\EcmRepasseOrigemTable;

/**
 * Repasse\Model\Table\EcmRepasseOrigemTable Test Case
 */
class EcmRepasseOrigemTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Repasse\Model\Table\EcmRepasseOrigemTable     */
    public $EcmRepasseOrigem;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.repasse.ecm_repasse_origem',
        'plugin.repasse.ecm_repasse',
        'plugin.repasse.mdl_user',
        'plugin.repasse.mdl_user_modified',
        'plugin.repasse.ecm_alternative_host'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmRepasseOrigem') ? [] : ['className' => 'Repasse\Model\Table\EcmRepasseOrigemTable'];        $this->EcmRepasseOrigem = TableRegistry::get('EcmRepasseOrigem', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmRepasseOrigem);

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
