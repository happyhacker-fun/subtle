<?php


namespace Subtle\Factory;

class Container
{
    /**
     * @var array
     */
    private static $objectContainer;

    /**
     * Prefer not creating a new object.
     *
     * @param string $className
     * @param mixed ...$args
     * @return mixed
     */
    public static function get(string $className, ...$args)
    {
        $index = self::objectIndex($className, $args);
        if (! isset(self::$objectContainer[$index])) {
            self::$objectContainer[$index] = self::make($className, ...$args);
        }

        return self::$objectContainer[$index];
    }

    /**
     * Prefer creating a new object.
     *
     * @param string $className
     * @param mixed ...$args
     * @return mixed
     */
    public static function make(string $className, ...$args)
    {
        $object = new $className(...$args);

        self::$objectContainer[self::objectIndex($className, $args)] = $object;
        return $object;
    }

    /**
     * Generate a unique id for indexing.
     *
     * @param string $className
     * @param mixed ...$args
     * @return string
     */
    private static function objectIndex(string $className, ...$args): string
    {
        return md5($className . json_encode($args));
    }
}