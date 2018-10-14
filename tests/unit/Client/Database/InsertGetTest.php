<?php namespace Client\Database;

use Faker\Factory;
use Illuminate\Database\Connection;
use Subtle\Factory\Container;
use Tests\Subtle\Client\Database\ArticleTable;

class InsertGetTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @throws \Subtle\Client\Database\InvalidDatabaseConfigException
     */
    protected function _before()
    {
        ini_set('serialize_precision', 14);

        \defined('APP_NAME') || \define('APP_NAME', 'subtle-app');
        \defined('LOG_DIR') || \define('LOG_DIR', '/tmp/' . APP_NAME);
        \defined('REQUEST_ID') || \define('REQUEST_ID', uniqid(APP_NAME, true));

        $config = Container::get(ArticleTable::class, 'subtle', false);
        $connection = Container::get(\Subtle\Client\Database\Connection::class);
        $this->db = $connection->create($config);

        $this->db->table('article')
            ->delete();
    }

    protected function _after()
    {
    }

    // tests
    public function testInsertBatch()
    {
        $faker = Factory::create();
        $lines = 5;

        $rows = [];
        while ($lines--) {
            $rows[] = [
                'title' => $faker->name,
                'content' => $faker->text,
            ];
        }

        $this->db->table('article')
            ->insert($rows);

        $total = $this->db->table('article')
            ->count();

        $this->tester->assertEquals(5, $total);
    }
}