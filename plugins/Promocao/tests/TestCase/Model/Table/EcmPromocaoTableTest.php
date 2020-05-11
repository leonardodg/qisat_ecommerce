<?php
namespace Promocao\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Promocao\Model\Table\EcmPromocaoTable;

/**
 * Promocao\Model\Table\EcmPromocaoTable Test Case
 */
class EcmPromocaoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Promocao\Model\Table\EcmPromocaoTable     */
    public $EcmPromocao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.promocao.ecm_promocao',
        'plugin.promocao.ecm_alternative_host',
        'plugin.promocao.ecm_promocao_ecm_alternative_host',
        'plugin.promocao.ecm_produto',
        'plugin.promocao.ecm_promocao_ecm_produto'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmPromocao') ? [] : ['className' => 'Promocao\Model\Table\EcmPromocaoTable'];        $this->EcmPromocao = TableRegistry::get('EcmPromocao', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmPromocao);

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
