<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/13
 * Time: 23:34
 */

namespace Subtle\Client\Database;


use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Events\Dispatcher;
use PDO;
use Subtle\Config\AbstractConfig;
use Subtle\Log\Log;

/**
 * Class Connection
 * @package Subtle\Client\Database
 */
class Connection
{
    private $module = 'DB';

    /**
     * Fetch database connection
     *
     * @param AbstractConfig $config
     * @return \Illuminate\Database\Connection
     * @throws InvalidDatabaseConfigException
     */
    public function create(AbstractConfig $config): \Illuminate\Database\Connection
    {
        $configure = $config->getConfig();

        $requiredKeys = [
            'driver',
            'host',
            'port',
            'database',
            'username',
            'password',
            'charset',
        ];

        if (($diff = array_diff($requiredKeys, array_keys($configure))) !== []) {
            throw new InvalidDatabaseConfigException('Required database config key(s) ' . implode('|', $diff) . ' are not set');
        }

        $capsule = new Manager();
        $capsule->addConnection($configure, $config->getAlias());
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $connection = $capsule->getConnection($config->getAlias());
        $connection->enableQueryLog();
        $connection->setEventDispatcher($this->setupEventDispatcher());

        return $connection;
    }

    /**
     * Register callback functions on important events
     *
     * @return Dispatcher
     */
    private function setupEventDispatcher(): Dispatcher
    {
        $dispatcher = new Dispatcher();

        $dispatcher->listen(StatementPrepared::class, function ($event) {
            $event->statement->setFetchMode(PDO::FETCH_ASSOC);
        });

        $dispatcher->listen(QueryExecuted::class, function ($event) {
            $sql = str_replace('?', "'%s'", $event->sql);
            $query = vsprintf($sql, $event->bindings);
            $context = ['connection' => $event->connectionName, 'sql' => $query, 'time' => $event->time];
            Log::info($this->module, $context);
        });

        return $dispatcher;
    }
}