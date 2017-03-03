<?php

namespace IcyApril\TPCrawler;

/**
 * Class App
 * @package IcyApril\TPCrawler
 */
class App
{
    const TPB = 'https://thepiratebay.se';

    public static function init()
    {
        Database::connect();
    }

    public static function install()
    {
        Database::connect();

        if (Database::$db) {
            Database::$db->query("CREATE TABLE IF NOT EXISTS `magnets` ("
                . " `id` INT(11) NOT NULL AUTO_INCREMENT,"
                . " `url` VARCHAR(1000) NOT NULL,"
                . " `magnet` VARCHAR(2555) NOT NULL,"
                . " `description` TEXT NOT NULL,"
                . " PRIMARY KEY (`id`),"
                . " UNIQUE KEY `url` (`url`)"
                . ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
        }
    }
}