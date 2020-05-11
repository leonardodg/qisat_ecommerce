<?php
namespace Indicacao\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Indicacao\Model\Table\EcmIndicacaoSegmentoTable;

/**
 * Indicacao\Model\Table\EcmIndicacaoSegmentoTable Test Case
 */
class EcmIndicacaoSegmentoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Indicacao\Model\Table\EcmIndicacaoSegmentoTable     */
    public $EcmIndicacaoSegmento;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.indicacao.ecm_indicacao_segmento',
        'plugin.indicacao.ecm_indicacao_curso'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmIndicacaoSegmento') ? [] : ['className' => 'Indicacao\Model\Table\EcmIndicacaoSegmentoTable'];        $this->EcmIndicacaoSegmento = TableRegistry::get('EcmIndicacaoSegmento', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmIndicacaoSegmento);

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
