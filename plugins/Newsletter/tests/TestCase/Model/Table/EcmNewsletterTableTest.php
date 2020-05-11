<?php
namespace Newsletter\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Newsletter\Model\Table\EcmNewsletterTable;

/**
 * Newsletter\Model\Table\EcmNewsletterTable Test Case
 */
class EcmNewsletterTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Newsletter\Model\Table\EcmNewsletterTable     */
    public $EcmNewsletter;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.newsletter.ecm_newsletter'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmNewsletter') ? [] : ['className' => 'Newsletter\Model\Table\EcmNewsletterTable'];        $this->EcmNewsletter = TableRegistry::get('EcmNewsletter', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmNewsletter);

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
