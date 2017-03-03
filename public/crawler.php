<?php

require '../vendor/autoload.php';

use IcyApril\TPCrawler;

ignore_user_abort(true);
set_time_limit(0);

header('Content-Type: application/json, text/json');
echo json_encode(
    [
        'success' => TPCrawler\Torrents::init()
    ]
);