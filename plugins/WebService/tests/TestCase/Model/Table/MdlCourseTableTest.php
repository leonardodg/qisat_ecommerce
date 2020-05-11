<?php
namespace WebService\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use WebService\Model\Table\MdlCourseTable;

/**
 * WebService\Model\Table\MdlCourseTable Test Case
 */
class MdlCourseTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \WebService\Model\Table\MdlCourseTable     */
    public $MdlCourse;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MdlCourse') ? [] : ['className' => 'WebService\Model\Table\MdlCourseTable'];        $this->MdlCourse = TableRegistry::get('MdlCourse', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        //unset($this->MdlCourse);
        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     *
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     *
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test matricula method
     *
     * @return void
     */
    public function testMatricula()
    {
        $usuario = isset($_SERVER['usuario']) ? intval($_SERVER['usuario']) : 56;
        $produto = isset($_SERVER['produto']) ? intval($_SERVER['produto']) : 304;//309
        $pago    = isset($_SERVER['pago'])    ? intval($_SERVER['pago'])    : true;
        $results = $this->MdlCourse->matricular($usuario, $produto, $pago);
        foreach($results as $result) {
            var_dump($result);
            $this->assertTrue($result->sucesso);
        }
    }

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
}
