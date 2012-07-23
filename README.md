# TPCrawler – ThePirateBay Anti-Censorship Tool
## About
In the wake of UK ISPs being forced to censor ThePirateBay, and Pirate Party amongst others offering a proxy to access the site that many indie artists use to get their content recognised; it came to my attention that aside from a 90MB backup of some of the torrents on the site, there were no large scale mirrors of the site. Upon realising this, I noted how impractical it would be simply wget the entire contents of the site.

It was for this reason that TPCrawler was born. TPCrawler is an open-source PHP tool to mirror magnet URLs, source URLs and the descriptions from torrents on ThePirateBay (and on other sites with simple modifications) entirely autonomously. It is a minimalistic tool that mirrors that stores information using MySQL with a search and viewing front-end.

To find people using this tool, you can simply search: ”Powered by TPCrawler.”

## Legal
The tool is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) licence. Note; this tool was created with the intent it is used to mirror content from artists that wish to get exposed or human rights workers who wish to make their findings known, who likewise offer their work up in a Copyright Free sense; free to share; CopyLeft, if you will; and not for the intent of illegal piracy. You can utilise the blacklist feature, and use the tool to only mirror content which is legal to download by modifying the script. This tool only mirrors “Magnet URLs”, which are essentially just hashes that point to a file, and cannot download/mirror any copyright material in any way on its own (unless you somehow own copyright on a computer hash). If any illegal magnets do creep though your filter, you can delete them and not download the files. Furthermore I do not host any live version of this tool at all, any live version is operated by people who are not personally affiliated to me in any way, all screenshots on this site have been obtained from these sites. Furthermore this work is provided as-is, no warranties or guaranties in any way shape or form. I do not directly profit commercially from this script in any way.

## Features

* Autonomous mirroring of magnet URLs, source URLs and descriptions on ThePirateBay by default, or any other script if the URL is changed (DOM values may need to be changed).
* Data is paginated and truncated descriptions on home to avoid massive page loads.
* Data stored in MySQL database.
* Search function.
* Open-source, simplistic PHP, HTML and a tiny tiny bit of CSS. No images, works best on LAMPP install (Linux, Apache, MySQL, PHPMyAdmin).
* Crawler starts on every page load, unless modified.
* The crawler script can be externally called by running this command on a Linux system: php crawler.php | /dev/null &

* This script has been very rapidly created, and uses everything I hate (MySQL, manipulating arrays, DOM). So do not expect perfection.

## Set-up
Set-up:
A) PHP Files; Upload the files to you web server to a directory of your choosing:
Download and upload the files from the link above. Alternatively WGET and upload the files.

B) MySQL:
You will need to edit the “connect.php” file to include your MySQL details:
`
<?php
/* Database config */

$db_host        = 'localhost'; // In most cases you should leave this alone.
$db_user        = 'root'; // MySQL username.
$db_pass        = ''; // MySQL password.
$db_database    = 'tpcrawler'; // MySQL database name.

/* End config */

$link = mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');

mysql_select_db($db_database,$link);
mysql_query("SET NAMES UTF8");/* Database config */
`
C) Simply open your web browser and go to www.yourdomain.com/tpcrawlerdirectory/crawler.php; changing the directory and domain name to your choosing.

To run the mirroring, even when no one is connected to the site, on a Linux server run this command: php /directory/to/crawler.php | /dev/null &

D) Browse:
Go to the web page where you have installed TPCrawler and you can browse what the script has mirrored!

## Old History (before move to GitHub)
History:
**V0.1 Alpha – 6/5/2012**

Initial release.

**V0.2 Alpha – 6/5/2012**

“Go to page” fix released.

**V0.3 Alpha – 7/5/2012**

Fixed page number count.
Added version count.

**V0.4 Alpha – 12/05/2012**

Reported issue of crawler stopping after navigation off the page has been fixed.

No more annoying, constantly loading iframe! :)
