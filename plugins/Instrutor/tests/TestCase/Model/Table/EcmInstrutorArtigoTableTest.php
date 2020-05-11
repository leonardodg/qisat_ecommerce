<?php
namespace Instrutor\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Instrutor\Model\Table\EcmInstrutorArtigoTable;

/**
 * Instrutor\Model\Table\EcmInstrutorArtigoTable Test Case
 */
class EcmInstrutorArtigoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Instrutor\Model\Table\EcmInstrutorArtigoTable     */
    public $EcmInstrutorArtigo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.instrutor.ecm_instrutor_artigo',
        'plugin.instrutor.ecm_instrutor',
        'plugin.instrutor.mdl_user',
        'plugin.instrutor.ecm_imagem',
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
        $config = TableRegistry::exists('EcmInstrutorArtigo') ? [] : ['className' => 'Instrutor\Model\Table\EcmInstrutorArtigoTable'];        $this->EcmInstrutorArtigo = TableRegistry::get('EcmInstrutorArtigo', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmInstrutorArtigo);

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
