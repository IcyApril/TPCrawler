<?php

require '../vendor/autoload.php';

use IcyApril\TPCrawler;

TPCrawler\App::init();
TPCrawler\App::install();

/*
VERSION 0.4
Licensed under the terms of CreativeCommons BY-NC-SA https://creativecommons.org/licenses/by-nc-sa/3.0/
Author: IcyApril - http://junade.com/2012/05/06/tpcrawler/ 

The tool is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) licence. Note; this tool was created with the intent it is used to mirror content from artists that wish to get exposed or human rights workers who wish to make their findings known, who likewise offer their work up in a Copyright Free sense; free to share; CopyLeft, if you will; and not for the intent of illegal piracy. You can utilise the blacklist feature, and use the tool to only mirror content which is legal to download by modifying the script. This tool only mirrors "Magnet URLs", which are essentially just hashes that point to a file, and cannot download/mirror any copyright material in any way on its own (unless you somehow own copyright on a computer hash). If any illegal magnets do creep though your filter, you can delete them and do download the files. Furthermore I do not host any live version of this tool at all, any live version is operated by people who are not personally affiliated to me in any way, all screenshots on this site have been obtained from these sites. Furthermore this work is provided as-is, no warranties or guaranties in any way shape or form. I do not directly profit commercially from this script in any way.

This crawler can be run via command line in the background by using:-> php crawler.php | /dev/null & 
*/
exec("/usr/bin/php crawler.php >/dev/null &");

$search = TPCrawler\Database::$db->escape_string($_GET['s']);
$id = TPCrawler\Database::$db->escape_string($_GET['id']);
$page = TPCrawler\Database::$db->escape_string($_GET['pages']);

if ($page) {
    $realpage = $page - 1;
    $lowerbound = $realpage * 50;
    $upperbound = (int)$lowerbound + 100;
    $nextpage = (int)$page + 1;
} else {
    $page = 1;
    $realpage = 0;
    $lowerbound = 0;
    $upperbound = 100;
    $nextpage = $page + 1;
}

if ($id) {
    $trunc = false;
    $query = TPCrawler\Database::$db->prepare("SELECT * FROM magnets WHERE id = ?");
    $query->bind_param('i', $id);
} else {
    $trunc = true;
    if ($search) {
        $query = TPCrawler\Database::$db->prepare("SELECT * FROM magnets WHERE url LIKE ? OR description LIKE ? OR magnet LIKE ?");
        $query->bind_param('sss', $search, $search, $search);
    } else {
        $query = TPCrawler\Database::$db->prepare("SELECT * FROM magnets WHERE id BETWEEN ? AND ?");
        $query->bind_param('ii', $lowerbound, $upperbound);
    }
}

$query_count = TPCrawler\Database::$db->prepare("SELECT COUNT(id) FROM magnets");
$totpages = 0;
$query_count->execute() or die(TPCrawler\Database::$db->error);
$query_count->bind_result($count);

while ($query_count->fetch()) {
    $totpages = intval($count / 100 + 1);
}

if (($page > $totpages) || ($page < 0)) {
    die('<p>No more pages. :(</p>');
}

$torrent = new stdClass();
$query->bind_result($torrent->id, $torrent->url, $torrent->magnet, $torrent->description);
$query->execute() or die(TPCrawler\Database::$db->error);
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="panel">
        <div class="panel-group">
            <h2>Browse Magnets</h2>
            <p><a href="index.php">Home</a></p>
            <form name="s" action="index.php" method="get">
                <input title="Search" type="search" name="s"/>
                <input type="submit" value="Search"/>
            </form>
        </div>
        <div class="panel-group">
            <table class="table">
                <tr>
                    <th>Source URL</th>
                    <th>Magnet URL</th>
                    <th>Description</th>
                    <th>More Info</th>
                </tr>
                <?php while ($query->fetch()) { ?>
                    <tr>
                        <td>
                            <a href="<?= $torrent->url ?>">Source</a>
                        </td>
                        <td>
                            <a href="<?= $torrent->magnet ?>">Download</a>
                        </td>
                        <td>
                            <?= ($trunc == true) ? TPCrawler\Content::truncate($torrent->description,
                                15) : nl2br($torrent->description) ?>
                        </td>
                        <td>
                            <a href="index.php?id=<?= $torrent->id ?>">Info</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="panel-group">
            <?php if (!($id || $search)) { ?>
                You are at page: <?= $page ?> of <?= $totpages ?> <a
                        href='index.php?pages=<?= $nextpage ?>'>Page: <?= $nextpage ?></a>
            <?php } ?>
        </div>
        <div class="panel-group">
            <form name="pages" action="index.php" method="get">
                Go to page: <input type="text" name="pages"/>
                <input type="submit" value="Go"/>
            </form>
        </div>
        <div class="panel-group">
            <hr/>
            <p>Powered by <a href="http://junade.omgthatsepic.com/2012/05/05/tpcrawler/ ">TPCrawler</a>. Licensed under
                the
                terms of <a href="https://creativecommons.org/licenses/by-nc-sa/3.0/">CreativeCommons BY-NC-SA</a></p>
        </div>
    </div>
</body>
</html>