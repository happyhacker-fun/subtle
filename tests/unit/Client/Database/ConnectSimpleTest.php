<?php namespace Client\Database;

use Faker\Factory;
use Subtle\Client\Database\Connection;
use Subtle\Factory\Container;
use Tests\Subtle\Client\Database\ArticleTable;

class ConnectSimpleTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        ini_set('serialize_precision', 14);

        \defined('APP_NAME') || \define('APP_NAME', 'subtle-app');
        \defined('LOG_DIR') || \define('LOG_DIR', '/tmp/' . APP_NAME);
        \defined('REQUEST_ID') || \define('REQUEST_ID', uniqid(APP_NAME, true));
    }

    protected function _after()
    {
    }

    // tests
    public function testConnect()
    {
        $config = Container::get(ArticleTable::class, 'subtle', false);
        $connection = Container::get(Connection::class);
        $db = $connection->create($config);
        $faker = Factory::create();
        $id = $db->table('article')
            ->insertGetId([
                'title' => $faker->name,
                'content' => $faker->text,
            ]);
        $this->tester->assertGreaterThan(0, $id);
    }
}