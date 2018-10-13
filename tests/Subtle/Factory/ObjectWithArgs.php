<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/14
 * Time: 00:21
 */

namespace Tests\Subtle\Factory;


class ObjectWithArgs
{
    private $name;

    private $mobile;


    public function __construct($name, $mobile)
    {
        $this->name = $name;
        $this->mobile = $mobile;
    }

    public function hello(): string
    {
        return 'hello ' . $this->name . ' please call ' . $this->mobile . ' to find me';
    }
}