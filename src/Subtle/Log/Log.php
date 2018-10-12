<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/13
 * Time: 00:16
 */

namespace Subtle\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class Log
 * @method static emergency($message, array $context = [])
 * @method static alert($message, array $context = [])
 * @method static critical($message, array $context = [])
 * @method static error($message, array $context = [])
 * @method static warning($message, array $context = [])
 * @method static notice($message, array $context = [])
 * @method static info($message, array $context = [])
 * @method static debug($message, array $context = [])
 * @method static log($level, $message, array $context = [])
 * @package SubtleFramework
 */
class Log
{
    private static $lineFormatter;
    private static $logger;
    private static $level = Logger::INFO;

    private static $defaultLineFormatter = '[%datetime%] [' . REQUEST_ID . "] %channel%.%level_name%: %message% %context% %extra%\n";

    public static function setLevel($level): void
    {
        self::$level = $level;
    }

    public static function getLevel(): int
    {
        return self::$level;
    }

    public static function setLineFormatter($lineFormatter): void
    {
        self::$lineFormatter = $lineFormatter;
    }

    public static function getLineFormatter(): string
    {
        if (null === self::$lineFormatter) {
            self::$lineFormatter = self::$defaultLineFormatter;
        }

        return self::$lineFormatter;
    }

    public static function getLogger(): LoggerInterface
    {
        self::setUpLogger();
        return self::$logger;
    }

    private static function setUpLogger(): void
    {
        if (null === self::$logger) {
            $logger = new Logger(APP_NAME);
            $handler = new RotatingFileHandler(LOG_DIR . '/app.log');
            $lineFormatter = new LineFormatter(
                self::getLineFormatter(),
                LineFormatter::SIMPLE_DATE,
                false,
                true
            );
            $handler->setFormatter($lineFormatter);
            $handler->setLevel(self::getLevel());
            $logger->pushHandler($handler);
            self::$logger = $logger;
        }
    }

    public static function __callStatic($name, $arguments)
    {
        self::setUpLogger();
        \call_user_func_array([self::$logger, $name], $arguments);
    }
}