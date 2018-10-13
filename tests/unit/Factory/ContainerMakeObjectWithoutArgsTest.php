<?php namespace Factory;

use Subtle\Factory\Container;
use Tests\Subtle\Factory\ObjectWithoutArgs;

class ContainerMakeObjectWithoutArgsTest extends \Codeception\Test\Unit
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
        $this->tester->assertEquals('world', Container::make(ObjectWithoutArgs::class)->hello());

    }

    public function testDoubleMake()
    {
        $object = Container::make(ObjectWithoutArgs::class);
        $objectId = spl_object_hash($object);

        $objectAgain = Container::make(ObjectWithoutArgs::class);
        $objectIdAgain = spl_object_hash($objectAgain);

        $this->tester->assertNotEquals($objectId, $objectIdAgain);
    }

    public function testGetMake()
    {
        $object = Container::get(ObjectWithoutArgs::class);
        $objectId = spl_object_hash($object);

        $objectAgain = Container::make(ObjectWithoutArgs::class);
        $objectIdAgain = spl_object_hash($objectAgain);

        $this->tester->assertNotEquals($objectId, $objectIdAgain);
    }
}