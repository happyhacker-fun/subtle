<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/14
 * Time: 19:52
 */

namespace Tests\Subtle\Client\Http;


use Subtle\Client\Http\Request;

class Method
{
    use Request;

    protected function serviceConfig(): array
    {
        return [
            'base_uri' => 'http://subtle_httpbin_1',
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
        ];
    }
}