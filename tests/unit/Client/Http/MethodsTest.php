<?php namespace Client\Http;

use Subtle\Factory\Container;
use Tests\Subtle\Client\Http\Method;

class MethodsTest extends \Codeception\Test\Unit
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
        $response = Container::get(Method::class)->send('get');

        $this->tester->assertEquals(200, $response->getStatusCode());
    }

    public function testPost()
    {
        $response = Container::get(Method::class)->send('post');

        $this->tester->assertEquals(200, $response->getStatusCode());
    }

    public function testPut()
    {
        $response = Container::get(Method::class)->send('put');

        $this->tester->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete()
    {
        $response = Container::get(Method::class)->send('delete');

        $this->tester->assertEquals(200, $response->getStatusCode());
    }

    public function testPatch()
    {
        $response = Container::get(Method::class)->send('patch');

        $this->tester->assertEquals(200, $response->getStatusCode());
    }
}