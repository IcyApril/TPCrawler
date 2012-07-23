<?php
/* Database config */

$db_host		= 'localhost'; // In most cases you should leave this alone.
$db_user		= 'root'; // MySQL username.
$db_pass		= ''; // MySQL password.
$db_database	= 'tpcrawler'; // MySQL database name.

/* End config */

$link = mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');

mysql_select_db($db_database,$link);
mysql_query("SET NAMES UTF8");/* Database config */
$sql = "CREATE TABLE IF NOT EXISTS `magnets` (\n"
    . " `id` int(11) NOT NULL AUTO_INCREMENT,\n"
    . " `url` varchar(1000) NOT NULL,\n"
    . " `magnet` varchar(2555) NOT NULL,\n"
    . " `description` text NOT NULL,\n"
    . " PRIMARY KEY (`id`),\n"
    . " UNIQUE KEY `url` (`url`)\n"
    . ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
    mysql_query($sql);



/*

Table stucture for magnets, you can enter this directly into PHPMyAdmin if the above gives you grief:
--
-- Table structure for table `magnets`
--

CREATE TABLE IF NOT EXISTS `magnets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(1000) NOT NULL,
  `magnet` varchar(2555) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

*/