<?php
namespace WebService\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use WebService\Model\Table\MdlUserEnrolmentsTable;

/**
 * WebService\Model\Table\MdlUserEnrolmentsTable Test Case
 */
class MdlUserEnrolmentsTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \WebService\Model\Table\MdlUserEnrolmentsTable     */
    public $MdlUserEnrolments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.web_service.mdl_user_enrolments'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MdlUserEnrolments') ? [] : ['className' => 'WebService\Model\Table\MdlUserEnrolmentsTable'];        $this->MdlUserEnrolments = TableRegistry::get('MdlUserEnrolments', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MdlUserEnrolments);

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
