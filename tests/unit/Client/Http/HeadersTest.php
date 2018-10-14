<?php namespace Client\Http;

use Subtle\Factory\Container;
use Subtle\Log\Log;
use Tests\Subtle\Client\Http\Headers;

class HeadersTest extends \Codeception\Test\Unit
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
    public function testHeaderInService()
    {
        $response = Container::get(Headers::class)->send('get');

        $this->tester->assertArrayHasKey('X-Header-A', $response['headers']);

    }

    public function testHeaderInApi()
    {
        $response = Container::get(Headers::class)->send('post');

        $this->tester->assertEquals('application/x-bbb', $response['headers']['Content-Type']);
    }

    public function testHeaderCover()
    {
        $response = Container::get(Headers::class)->send('put');
        Log::info('response', $response);
        $this->tester->assertEquals('x-header-b', $response['headers']['X-Header-A']);
    }

    public function testHeaderCoverLevel3()
    {
        $response = Container::get(Headers::class)->send('put', [
            'headers' => [
                'X-HEADER-A' => 'x-header-c',
            ]
        ]);
        Log::info('response', $response);
        $this->tester->assertEquals('x-header-c', $response['headers']['X-Header-A']);
    }
}