<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/14
 * Time: 19:52
 */

namespace Tests\Subtle\Client\Http;


use Subtle\Client\Http\Request;

class Headers
{
    use Request;

    protected function serviceConfig(): array
    {
        return [
            'base_uri' => 'http://subtle_httpbin_1',
            'response_handler' => 'default',
            'headers' => [
                'X-HEADER-A' => 'x-header-a',
            ]
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
                'headers' => [
                    'Content-Type' => 'application/x-bbb',
                ],
            ],
            'put' => [
                'method' => 'put',
                'path' => 'put',
                'headers' => [
                    'X-HEADER-A' => 'x-header-b',
                ],
            ],
            'delete' => [
                'method' => 'delete',
                'path' => 'delete',
            ],
            'patch' => [
                'method' => 'patch',
                'path' => 'patch',
            ],
        ];
    }
}