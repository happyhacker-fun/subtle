<?php

namespace Tests\Subtle;

use Subtle\Client\Http\Request;

/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/13
 * Time: 00:59
 */

class Gank
{
    use Request;

    public function __construct()
    {
    }

    public function serviceConfig()
    {
        return [
            'base_uri' => 'https://gank.io',
        ];
    }

    public function apiConfig($api)
    {
        return [
            'method' => 'GET',
            'path' => '/api/today',
        ];
    }
}