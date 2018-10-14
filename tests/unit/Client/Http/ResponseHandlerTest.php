<?php namespace Client\Http;

use Subtle\Factory\Container;
use Tests\Subtle\Client\Http\ResponseHandler;

class ResponseHandlerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testGet()
    {
        $response = Container::get(ResponseHandler::class)->send('get');

        $this->tester->assertArrayHasKey('headers', $response);
    }

    public function testPost()
    {
        $response = Container::get(ResponseHandler::class)->send('post');

        $this->tester->assertArrayHasKey('headers', $response);
    }

    public function testPut()
    {
        $response = Container::get(ResponseHandler::class)->send('put');

        $this->tester->assertArrayHasKey('headers', $response);
    }

    public function testDelete()
    {
        $response = Container::get(ResponseHandler::class)->send('delete');

        $this->tester->assertArrayHasKey('headers', $response);
    }

    public function testPatch()
    {
        $response = Container::get(ResponseHandler::class)->send('patch');

        $this->tester->assertArrayHasKey('headers', $response);
    }

    public function testCustomHandler()
    {
        $response = Container::get(ResponseHandler::class)->send('get_with_handler');

        $this->tester->assertEquals(200, $response);
    }
}