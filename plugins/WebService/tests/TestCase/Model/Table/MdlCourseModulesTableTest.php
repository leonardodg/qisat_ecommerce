<?php
namespace WebService\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use WebService\Model\Table\MdlCourseModulesTable;

/**
 * WebService\Model\Table\MdlCourseModulesTable Test Case
 */
class MdlCourseModulesTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \WebService\Model\Table\MdlCourseModulesTable     */
    public $MdlCourseModules;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.web_service.mdl_course_modules'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MdlCourseModules') ? [] : ['className' => 'WebService\Model\Table\MdlCourseModulesTable'];        $this->MdlCourseModules = TableRegistry::get('MdlCourseModules', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MdlCourseModules);

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
