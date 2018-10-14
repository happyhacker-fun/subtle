<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/14
 * Time: 11:15
 */

namespace Tests\Subtle\Client\Database;


use Subtle\Config\AbstractConfig;

class ArticleTable extends AbstractConfig
{
    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'driver' => 'mysql',
            'host' => 'subtle_db_1',
            'port' => '3306',
            'database' => 'subtle',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
        ];
    }
}