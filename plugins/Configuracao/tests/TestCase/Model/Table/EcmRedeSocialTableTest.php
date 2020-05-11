<?php
namespace Configuracao\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Configuracao\Model\Table\EcmRedeSocialTable;

/**
 * Configuracao\Model\Table\EcmRedeSocialTable Test Case
 */
class EcmRedeSocialTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Configuracao\Model\Table\EcmRedeSocialTable     */
    public $EcmRedeSocial;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.configuracao.ecm_rede_social',
        'plugin.configuracao.ecm_imagem',
        'plugin.configuracao.ecm_instrutor_rede_social'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmRedeSocial') ? [] : ['className' => 'Configuracao\Model\Table\EcmRedeSocialTable'];        $this->EcmRedeSocial = TableRegistry::get('EcmRedeSocial', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmRedeSocial);

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
