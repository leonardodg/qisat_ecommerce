<?php
namespace App\Test\TestCase\View\Cell;

use App\View\Cell\ElfinderCell;
use Cake\TestSuite\TestCase;

/**
 * App\View\Cell\ElfinderCell Test Case
 */
class ElfinderCellTest extends TestCase
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
     * @var \App\View\Cell\ElfinderCell     */
    public $Elfinder;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = $this->getMock('Cake\Network\Request');
        $this->response = $this->getMock('Cake\Network\Response');        $this->Elfinder = new ElfinderCell($this->request, $this->response);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Elfinder);

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
