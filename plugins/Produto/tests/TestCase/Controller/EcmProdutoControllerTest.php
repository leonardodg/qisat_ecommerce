<?php
namespace Produto\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use Produto\Controller\EcmProdutoController;

/**
 * Produto\Controller\EcmProdutoController Test Case
 */
class EcmProdutoControllerTest extends IntegrationTestCase
{

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        //$this->markTestIncomplete('Not implemented yet.');

        /*$this->session(['Auth' => [
            'User' => [
                'id' => 132,
                'permissoes' => [
                    'acesso_total' => true
                ]
            ]
        ]]);*/
    }

    public function test()
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Host' => 'http://local-site.qisat.com.br:90/'
            ]
        ]);
        $this->post('/wsc-user/login', ['username' => 'inty.castillo', 'password' => 'shadow20']);
        $this->get('/produto?page=1');
        $this->assertResponseOk();

        //$this->assertResponseContains('john.doe');
    }
}
