<?php
namespace Imagem\Test\TestCase\View\Cell;

use Cake\TestSuite\TestCase;
use Imagem\View\Cell\EcmImagemCell;

/**
 * Imagem\View\Cell\EcmImagemCell Test Case
 */
class EcmImagemCellTest extends TestCase
{

    /**
     * Request mock     *
     * @var \Cake\Network\Request|\PHPUnit_Framework_MockObject_MockObject     */
    public $request;

    /**
     * Response mock     *
     * @var \Cake\Network\Response|\PHPUnit_Framework_MockObject_MockObject     */
    public $response;

    /**
     * Test subject     *
     * @var \Imagem\View\Cell\EcmImagemCell     */
    public $EcmImagem;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = $this->getMock('Cake\Network\Request');
        $this->response = $this->getMock('Cake\Network\Response');        $this->EcmImagem = new EcmImagemCell($this->request, $this->response);    }

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
     * Test display method
     *
     * @return void
     */
    public function testDisplay()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
