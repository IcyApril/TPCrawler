<?php

require '../vendor/autoload.php';

use IcyApril\TPCrawler;

/*
VERSION 0.4
Licensed under the terms of CreativeCommons BY-NC-SA https://creativecommons.org/licenses/by-nc-sa/3.0/
Author: IcyApril - http://junade.com/2012/05/06/tpcrawler/ 

The tool is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) licence. Note; this tool was created with the intent it is used to mirror content from artists that wish to get exposed or human rights workers who wish to make their findings known, who likewise offer their work up in a Copyright Free sense; free to share; CopyLeft, if you will; and not for the intent of illegal piracy. You can utilise the blacklist feature, and use the tool to only mirror content which is legal to download by modifying the script. This tool only mirrors "Magnet URLs", which are essentially just hashes that point to a file, and cannot download/mirror any copyright material in any way on its own (unless you somehow own copyright on a computer hash). If any illegal magnets do creep though your filter, you can delete them and do download the files. Furthermore I do not host any live version of this tool at all, any live version is operated by people who are not personally affiliated to me in any way, all screenshots on this site have been obtained from these sites. Furthermore this work is provided as-is, no warranties or guaranties in any way shape or form. I do not directly profit commercially from this script in any way.

This crawler can be run via command line in the background by using:-> php crawler.php | /dev/null & 
*/
exec("/usr/bin/php crawler.php >/dev/null &");

function trunc($phrase, $max_words)
{
    $phrase_array = explode(' ', $phrase);
    if (count($phrase_array) > $max_words && $max_words > 0) {
        $phrase = implode(' ', array_slice($phrase_array, 0, $max_words)) . '&hellip;';
    }
    return $phrase;
}

?>

<link rel="stylesheet" href="../style.css" type="text/css" media="screen"/>

<?php
$search = htmlspecialchars(mysql_escape_string(($_GET['s'])));
$id = htmlspecialchars(mysql_escape_string(($_GET['id'])));
$page = htmlspecialchars(mysql_escape_string(($_GET['pages'])));

if ($page) {
    $realpage = $page - 1;
    $lowerbound = $realpage * 50;
    $upperbound = $lowerbound + 100;
    $nextpage = $page + 1;
} else {
    $page = 1;
    $realpage = 0;
    $lowerbound = 0;
    $upperbound = 100;
    $nextpage = $page + 1;
}

if ($id) {
    $trunc = false;
    $quey1 = "SELECT * FROM magnets WHERE id='$id'";
} else {
    if ($search) {
        $trunc = true;
        $quey1 = "SELECT * FROM magnets WHERE url LIKE '%$search%' OR description LIKE '%$search%' OR magnet LIKE '%$search%'";
    } else {
        $trunc = true;
        $quey1 = "SELECT * FROM magnets WHERE id BETWEEN $lowerbound AND $upperbound";
    }
}

$pg1 = "SELECT COUNT(id) FROM magnets";
$pgresult = mysql_query($pg1) or die(mysql_error());
while ($row = mysql_fetch_array($pgresult)) {
    $totpages = intval($row['COUNT(id)'] / 100 + 1);
}

if (($page > $totpages) || ($page < 0)) {
    die('<p>No more pages. :(</p>');
}

$result = mysql_query($quey1) or die(mysql_error());
?>
<center><h2>Browse Magnets</h2></center>
<p><a href="index.php">Home</a></p>
<form name="s" action="index.php" method="get">
    <input type="search" name="s"/>
    <input type="submit" value="Search"/>
</form>
<table cellpadding="0" cellspacing="0" class="db-table">
    <tr>
        <th>Source URL</th>
        <th>Magnet URL</th>
        <th>Description</th>
        <th>More Info</th>
    </tr>
    <?php
    while ($row = mysql_fetch_array($result)) {
        echo "</td><td>";
        echo '<a href="' . $row['url'] . '">Source</a>';
        echo "</td><td>";
        echo '<a href="' . $row['magnet'] . '">Download</a>';
        echo "</td><td>";
        if ($trunc == true) {
            echo trunc($row['description'], 15);
        } else {
            echo nl2br($row['description']);
        }
        echo "</td><td>";
        echo '<a href="index.php?id=' . $row['id'] . '">Info</a>';
        echo "</td></tr>";
    }
    echo "</table>";
    ?>
    <?php if (!($id || $search)) { ?>
        You are at page: <?= $page ?> of <?= $totpages ?> <a
                href='index.php?pages=<?= $nextpage ?>'>Page: <?= $nextpage ?></a>
    <?php } ?>
    <form name="pages" action="index.php" method="get">
        Go to page: <input type="text" name="pages"/>
        <input type="submit" value="Go"/>
    </form>
    <hr/>
    <p>Powered by <a href="http://junade.omgthatsepic.com/2012/05/05/tpcrawler/ ">TPCrawler</a>. Licensed under the
        terms of <a href="https://creativecommons.org/licenses/by-nc-sa/3.0/">CreativeCommons BY-NC-SA</a></p>
