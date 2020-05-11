<?php
namespace WebService\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use WebService\Model\Table\MdlEstadoTable;

/**
 * WebService\Model\Table\MdlEstadoTable Test Case
 */
class MdlEstadoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \WebService\Model\Table\MdlEstadoTable     */
    public $MdlEstado;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.web_service.mdl_estado'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MdlEstado') ? [] : ['className' => 'WebService\Model\Table\MdlEstadoTable'];        $this->MdlEstado = TableRegistry::get('MdlEstado', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MdlEstado);

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
