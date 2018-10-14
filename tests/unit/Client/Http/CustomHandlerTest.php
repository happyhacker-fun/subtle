<?php namespace Client\Http;

use Subtle\Factory\Container;
use Subtle\Log\Log;
use Tests\Subtle\Client\Http\CustomHandler;

class CustomHandlerTest extends \Codeception\Test\Unit
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
    public function testHandler()
    {
        $response = Container::get(CustomHandler::class)->send('get');

        $this->tester->assertArrayHasKey('X-Foo', $response['headers']);
    }

    public function testMultiHandlers()
    {
        $response = Container::get(CustomHandler::class)->send('get_response');

        $this->tester->assertEquals('foo', $response->getHeaderLine('X-Bar'));
    }
}