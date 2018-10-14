<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/14
 * Time: 10:39
 */

namespace Subtle\Config;

/**
 * Interface ConfigInterface
 * @package Subtle\Config
 */
abstract class AbstractConfig
{
    /**
     * @var string
     */
    protected $alias;

    /**
     * @var bool
     */
    protected $isMaster;


    /**
     * ConfigInterface constructor.
     * @param string $alias
     * @param bool $isMaster
     */
    public function __construct(string $alias, bool $isMaster = false)
    {
        $this->alias = $alias;
        $this->isMaster = $isMaster;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function isMaster(): bool
    {
        return $this->isMaster;
    }

    /**
     * @return array
     */
    abstract public function getConfig(): array;
}