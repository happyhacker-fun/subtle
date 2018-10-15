<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/14
 * Time: 19:52
 */

namespace Tests\Subtle\Client\Http;


use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Subtle\Client\Http\Request;

class CustomHandler
{
    use Request;

    protected function serviceConfig(): array
    {
        return [
            'base_uri' => 'http://subtle_httpbin_1',
            'response_handler' => 'default',
            'handlers' => [
                Middleware::mapRequest(function (RequestInterface $request) {
                    return $request->withAddedHeader('X-Foo', 'bar');
                }),
                Middleware::mapResponse(function (ResponseInterface $response) {
                    return $response->withAddedHeader('X-Bar', 'foo');
                }),
            ],
        ];
    }

    protected function apiConfig($api): array
    {
        return $this->apis()[$api];
    }

    protected function apis(): array
    {
        return [
            'get' => [
                'method' => 'get',
                'path' => 'get',
            ],
            'get_response' => [
                'method' => 'get',
                'path' => 'get',
                'response_handler' => null,
            ],
        ];
    }
}