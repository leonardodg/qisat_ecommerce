<?php
namespace Publicidade\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Publicidade\Model\Table\EcmPublicidadeTable;

/**
 * Publicidade\Model\Table\EcmPublicidadeTable Test Case
 */
class EcmPublicidadeTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Publicidade\Model\Table\EcmPublicidadeTable     */
    public $EcmPublicidade;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.publicidade.ecm_publicidade',
        'plugin.publicidade.ecm_produto'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmPublicidade') ? [] : ['className' => 'Publicidade\Model\Table\EcmPublicidadeTable'];        $this->EcmPublicidade = TableRegistry::get('EcmPublicidade', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmPublicidade);

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
