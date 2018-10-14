<?php namespace Factory;

use Subtle\Factory\Container;
use Tests\Subtle\Factory\ObjectWithoutArgs as ObjectTest;

class ContainerGetAliasTest extends \Codeception\Test\Unit
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
    public function testGetAlias(): void
    {
        $object = Container::get(ObjectTest::class);

        $this->tester->assertEquals('world', $object->hello());
    }
}