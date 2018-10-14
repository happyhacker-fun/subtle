<?php namespace Client\Http;

use Subtle\Factory\Container;
use Tests\Subtle\Client\Http\Timeout;

class TimeoutTest extends \Codeception\Test\Unit
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
    public function testTimeoutInService()
    {
        try {
            Container::get(Timeout::class)->send('delay_default');
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->tester->assertContains('cURL error 28: Operation timed out after 2', $e->getMessage());
        }
    }

    public function testDelayService()
    {
        $response = Container::get(Timeout::class)->send('delay_service');

        $this->tester->assertEquals(200, $response->getStatusCode());
    }

    public function testDelayApi()
    {
        try {
            Container::get(Timeout::class)->send('delay_api');
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->tester->assertContains('cURL error 28: Operation timed out after 1', $e->getMessage());
        }
    }

    public function testDelayDynamic()
    {
        $response = Container::get(Timeout::class)->send('delay_api', ['timeout' => 4]);

        $this->tester->assertEquals(200, $response->getStatusCode());
    }
}