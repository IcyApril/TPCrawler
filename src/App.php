<?php

namespace IcyApril\TPCrawler;

/**
 * Class App
 * @package IcyApril\TPCrawler
 */
class App
{
    public static function install()
    {
        Database::connect();

        Database::$db->query("CREATE TABLE IF NOT EXISTS `magnets` ("
            . " `id` int(11) NOT NULL AUTO_INCREMENT,"
            . " `url` varchar(1000) NOT NULL,"
            . " `magnet` varchar(2555) NOT NULL,"
            . " `description` text NOT NULL,"
            . " PRIMARY KEY (`id`),"
            . " UNIQUE KEY `url` (`url`)"
            . ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
    }
}