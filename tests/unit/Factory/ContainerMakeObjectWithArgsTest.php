<?php namespace Factory;

use Subtle\Factory\Container;
use Tests\Subtle\Factory\ObjectWithArgs;

class ContainerMakeObjectWithArgsTest extends \Codeception\Test\Unit
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
    public function testMake()
    {
        $name = 'John';
        $mobile = '123456';
        $this->tester->assertEquals('hello ' . $name . ' please call ' . $mobile . ' to find me', Container::make(ObjectWithArgs::class, $name, $mobile)->hello());
    }

    public function testDoubleMake()
    {
        $name = 'John';
        $mobile = '123456';
        $object1 = Container::make(ObjectWithArgs::class, $name, $mobile);
        $object2 = Container::make(ObjectWithArgs::class, $name, $mobile);

        $this->tester->assertNotEquals(spl_object_hash($object1), spl_object_hash($object2));
    }

    public function testMakeGet()
    {
        $name = 'John';
        $mobile = '123456';
        $object1 = Container::make(ObjectWithArgs::class, $name, $mobile);
        $object2 = Container::get(ObjectWithArgs::class, $name, $mobile);

        $this->tester->assertEquals(spl_object_hash($object1), spl_object_hash($object2));
    }
}