<?php
namespace WebService\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use WebService\Model\Table\EcmValidacaoRecaptchaTable;

/**
 * WebService\Model\Table\EcmValidacaoRecaptchaTable Test Case
 */
class EcmValidacaoRecaptchaTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \WebService\Model\Table\EcmValidacaoRecaptchaTable     */
    public $EcmValidacaoRecaptcha;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.web_service.ecm_validacao_recaptcha'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmValidacaoRecaptcha') ? [] : ['className' => 'WebService\Model\Table\EcmValidacaoRecaptchaTable'];        $this->EcmValidacaoRecaptcha = TableRegistry::get('EcmValidacaoRecaptcha', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmValidacaoRecaptcha);

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
