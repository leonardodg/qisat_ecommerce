<?php
namespace Instrutor\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Instrutor\Model\Table\EcmInstrutorTable;

/**
 * Instrutor\Model\Table\EcmInstrutorTable Test Case
 */
class EcmInstrutorTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Instrutor\Model\Table\EcmInstrutorTable     */
    public $EcmInstrutor;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.instrutor.ecm_instrutor',
        'plugin.instrutor.mdl_user',
        'plugin.instrutor.ecm_imagem',
        'plugin.instrutor.ecm_instrutor_artigo',
        'plugin.instrutor.ecm_instrutor_rede_social',
        'plugin.instrutor.ecm_produto',
        'plugin.instrutor.ecm_instrutor_ecm_produto'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmInstrutor') ? [] : ['className' => 'Instrutor\Model\Table\EcmInstrutorTable'];        $this->EcmInstrutor = TableRegistry::get('EcmInstrutor', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmInstrutor);

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
