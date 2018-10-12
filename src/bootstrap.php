<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/13
 * Time: 00:17
 */

ini_set('serialize_precision', 14);

defined('APP_NAME') || define('APP_NAME', 'subtle-app');
defined('LOG_DIR') || define('LOG_DIR', '/tmp/' . APP_NAME);
defined('REQUEST_ID') || define('REQUEST_ID', uniqid(APP_NAME, true));