<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/14
 * Time: 19:52
 */

namespace Tests\Subtle\Client\Http;


use Subtle\Client\Http\Request;

class Timeout
{
    use Request;

    protected function serviceConfig(): array
    {
        return [
            'base_uri' => 'http://subtle_httpbin_1',
            'timeout' => 4,
        ];
    }

    protected function apiConfig($api): array
    {
        return $this->apis()[$api];
    }

    protected function apis(): array
    {
        return [
            'delay_default' => [
                'method' => 'get',
                'path' => '/delay/3',
            ],
            'delay_service' => [
                'method' => 'get',
                'path' => '/delay/3',
            ],
            'delay_api' => [
                'method' => 'get',
                'path' => '/delay/3',
                'timeout' => 1,
            ],
        ];
    }
}