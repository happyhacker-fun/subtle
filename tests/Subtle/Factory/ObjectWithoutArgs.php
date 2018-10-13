<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/14
 * Time: 00:19
 */

namespace Tests\Subtle\Factory;


class ObjectWithoutArgs
{
    public function hello(): string
    {
        return 'world';
    }
}