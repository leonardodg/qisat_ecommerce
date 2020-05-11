<?php
namespace Repasse\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Repasse\Model\Table\EcmRepasseCategoriasTable;

/**
 * Repasse\Model\Table\EcmRepasseCategoriasTable Test Case
 */
class EcmRepasseCategoriasTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Repasse\Model\Table\EcmRepasseCategoriasTable     */
    public $EcmRepasseCategorias;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.repasse.ecm_repasse_categorias'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmRepasseCategorias') ? [] : ['className' => 'Repasse\Model\Table\EcmRepasseCategoriasTable'];        $this->EcmRepasseCategorias = TableRegistry::get('EcmRepasseCategorias', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmRepasseCategorias);

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
