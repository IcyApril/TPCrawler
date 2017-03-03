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

$search = isset($_GET['s']) ? TPCrawler\Database::$db->escape_string($_GET['s']) : false;
$id = isset($_GET['id']) ? (int)TPCrawler\Database::$db->escape_string($_GET['id']) : false;
$page = isset($_GET['pages']) ? (int)TPCrawler\Database::$db->escape_string($_GET['pages']) : false;

if ($page) {
    $realPage = $page - 1;
    $lowerBound = $realPage * 50;
    $upperBound = (int)$lowerBound + 100;
} else {
    $page = 1;
    $realPage = 0;
    $lowerBound = 0;
    $upperBound = 100;
}

if ($id) {
    $truncateString = false;
    $query = TPCrawler\Database::$db->prepare("SELECT * FROM magnets WHERE id = ?");
    $query->bind_param('i', $id);
} else {
    $truncateString = true;
    if ($search) {
        $query = TPCrawler\Database::$db->prepare("SELECT * FROM magnets WHERE url LIKE ? OR description LIKE ? OR magnet LIKE ?");
        $query->bind_param('sss', $search, $search, $search);
    } else {
        $query = TPCrawler\Database::$db->prepare("SELECT * FROM magnets WHERE id BETWEEN ? AND ?");
        $query->bind_param('ii', $lowerBound, $upperBound);
    }
}

$query_count = TPCrawler\Database::$db->prepare("SELECT COUNT(id) FROM magnets");
$totalPages = 0;
$query_count->execute() or die(TPCrawler\Database::$db->error);
$query_count->bind_result($count);

while ($query_count->fetch()) {
    $totalPages = intval($count / 100 + 1);
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
    <h1>Browse Magnets</h1>
    <div class="panel">
        <div class="panel-body">
            <div class="panel-group">
                <ul class="nav nav-pills">
                    <li role="presentation" class="active">
                        <a href="/">Home</a>
                    </li>
                </ul>
            </div>
            <div class="panel-group">
                <form class="form-inline" name="s" action="/" method="get">
                    <div class="form-group">
                        <input class="form-control" title="Search" type="search" name="s"
                               value="<?= ($search) ? $search : '' ?>"/>
                        <input class="btn btn-default" type="submit" value="Search"/>
                    </div>
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
                                <?= ($truncateString == true) ? TPCrawler\Content::truncate($torrent->description,
                                    15) : nl2br($torrent->description) ?>
                            </td>
                            <td>
                                <a href="/?id=<?= $torrent->id ?>">Info</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <?php if (!($id || $search)) { ?>
                <div class="panel-group">
                    <form class="form-inline" name="pages" action="/" method="get">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li>
                                    <a <?= $page > 1 ? 'href="/?pages=' . ($page - 1) . '"' : '' ?>
                                            aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a>Current: <?= $page ?></a>
                                </li>
                                <li>
                                    <a role="button" data-toggle="collapse" href="#pageToggle"
                                       aria-expanded="false" aria-controls="pageToggle">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </a>
                                </li>
                                <li>
                                    <a <?= $page > $totalPages ? 'href="/?pages=' . ($page + 1) . '"' : '' ?>
                                            aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <div class="collapse" id="pageToggle">
                            <label for="pages">Go to page:</label>
                            <input class="form-control" type="text" name="pages" id="pages"/>
                            <input class="btn btn-default" type="submit" value="Go"/>
                        </div>
                    </form>
                </div>
            <?php } ?>
            <hr/>
            <div class="panel-group">
                <p>
                    Licensed under the terms of <a href="https://creativecommons.org/licenses/by-nc-sa/3.0/">CreativeCommons
                        BY-NC-SA</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>