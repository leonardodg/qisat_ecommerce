<?php
namespace WebService\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use WebService\Model\Table\MdlCidadeTable;

/**
 * WebService\Model\Table\MdlCidadeTable Test Case
 */
class MdlCidadeTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \WebService\Model\Table\MdlCidadeTable     */
    public $MdlCidade;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.web_service.mdl_cidade',
        'plugin.web_service.ecm_convenio',
        'plugin.web_service.ecm_curso_presencial_local'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MdlCidade') ? [] : ['className' => 'WebService\Model\Table\MdlCidadeTable'];        $this->MdlCidade = TableRegistry::get('MdlCidade', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MdlCidade);

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
