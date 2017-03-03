<?php

namespace IcyApril\TPCrawler;

/**
 * Class Database
 * @package IcyApril\TPCrawler
 */
class Database
{
    // Database Config
    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASS = 'root';
    const DB_NAME = 'tpcrawler';

    /**
     * @var \mysqli
     */
    public static $db;

    /**
     * @return \mysqli
     */
    public static function connect()
    {
        if (!isset(self::$db)) {
            self::$db = new \mysqli(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);
        }

        return self::$db;
    }
}