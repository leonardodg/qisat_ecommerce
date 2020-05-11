<?php
namespace Imagem\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Imagem\Model\Table\EcmImagemTable;

/**
 * Imagem\Model\Table\EcmImagemTable Test Case
 */
class EcmImagemTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \Imagem\Model\Table\EcmImagemTable     */
    public $EcmImagem;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.imagem.ecm_imagem',
        'plugin.imagem.ecm_instrutor',
        'plugin.imagem.ecm_operadora_pagamento',
        'plugin.imagem.ecm_rede_social',
        'plugin.imagem.ecm_produto',
        'plugin.imagem.ecm_produto_ecm_imagem'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EcmImagem') ? [] : ['className' => 'Imagem\Model\Table\EcmImagemTable'];        $this->EcmImagem = TableRegistry::get('EcmImagem', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EcmImagem);

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
