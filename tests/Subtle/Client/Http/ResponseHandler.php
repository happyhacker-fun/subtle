<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/14
 * Time: 20:12
 */

namespace Tests\Subtle\Client\Http;


use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Subtle\Client\Http\Request;

class ResponseHandler
{
    use Request;

    protected function serviceConfig(): array
    {
        return [
            'base_uri' => 'http://subtle_httpbin_1',
            'response_handler' => 'default',
            'handlers' => [
                Middleware::mapRequest(function (\GuzzleHttp\Psr7\Request $request) {
                    return $request->withAddedHeader('X-Foo', 'bar');
                })
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
            'post' => [
                'method' => 'post',
                'path' => 'post',
            ],
            'put' => [
                'method' => 'put',
                'path' => 'put',
            ],
            'delete' => [
                'method' => 'delete',
                'path' => 'delete',
            ],
            'patch' => [
                'method' => 'patch',
                'path' => 'patch',
            ],
            'get_with_handler' => [
                'method' => 'get',
                'path' => 'get',
                'response_handler' => function (Response $response) {
                    return $response->getStatusCode();
                }
            ]
        ];
    }
}