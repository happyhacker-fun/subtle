<?php namespace Factory;

use Subtle\Factory\Container;
use Tests\Subtle\Factory\ObjectWithoutArgs;

class ContainerGetObjectWithoutArgsTest extends \Codeception\Test\Unit
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

    public function testGet()
    {
        $object = Container::get(ObjectWithoutArgs::class);

        $this->tester->assertEquals('world', $object->hello());
    }

    // tests
    public function testDoubleGet()
    {
        $object = Container::get(ObjectWithoutArgs::class);
        $objectId = spl_object_hash($object);

        $objectAgain = Container::get(ObjectWithoutArgs::class);
        $objectIdAgain = spl_object_hash($objectAgain);

        $this->tester->assertEquals($objectId, $objectIdAgain);
    }

    public function testMakeGet()
    {
        $object = Container::make(ObjectWithoutArgs::class);
        $objectId = spl_object_hash($object);

        $objectAgain = Container::get(ObjectWithoutArgs::class);
        $objectIdAgain = spl_object_hash($objectAgain);

        $this->tester->assertEquals($objectId, $objectIdAgain);
    }
}