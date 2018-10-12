<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/13
 * Time: 01:01
 */

use Tests\Subtle\Gank;

require __DIR__ . '/../src/bootstrap.php';

require __DIR__ . '/../vendor/autoload.php';

$gank = new Gank();

$response = $gank->send('api');
var_dump($response);