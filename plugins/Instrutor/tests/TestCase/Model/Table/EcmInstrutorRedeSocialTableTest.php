<?php
namespace Instrutor\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Instrutor\Model\Table\EcmInstrutorRedeSocialTable;

/**
 * Instrutor\Model\Table\EcmInstrutorRedeSocialTable Test Case
 */
class EcmInstrutorRedeSocialTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Instrutor\Model\Table\EcmInstrutorRedeSocialTable     */
    public $EcmInstrutorRedeSocial;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.instrutor.ecm_instrutor_rede_social',
        'plugin.instrutor.ecm_instrutor',
        'plugin.instrutor.mdl_user',
        'plugin.instrutor.ecm_imagem',
        'plugin.instrutor.ecm_instrutor_artigo',
        'plugin.instrutor.ecm_produto',
        'plugin.instrutor.ecm_instrutor_ecm_produto',
        'plugin.instrutor.ecm_rede_social'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmInstrutorRedeSocial') ? [] : ['className' => 'Instrutor\Model\Table\EcmInstrutorRedeSocialTable'];        $this->EcmInstrutorRedeSocial = TableRegistry::get('EcmInstrutorRedeSocial', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmInstrutorRedeSocial);

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
