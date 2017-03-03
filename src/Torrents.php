<?php

namespace IcyApril\TPCrawler;

/**
 * Class Torrents
 * @package IcyApril\TPCrawler
 */
class Torrents
{
    /**
     * @return bool
     */
    public static function init()
    {
        Database::connect();

        $torrents = self::getTorrents(App::TPB . '/top/48hall');

        if (!$torrents) {
            return false;
        }

        foreach ($torrents as $torrent) {
            self::addTorrent($torrent);
        }

        return true;
    }

    /**
     * @param string $url
     * @return array|bool
     */
    public static function getTorrents($url)
    {
        $urlList = [];
        $html = @file_get_contents($url);
        if (!$html) {
            return false;
        }
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);

        // grab all the on the page
        $xpath = new \DOMXPath($dom);
        $linksList = $xpath->evaluate("/html/body//a");
        $torrent_id = false;

        foreach ($linksList as $link) {
            $url = $link->getAttribute('href');
            if (preg_match('/^\/torrent\//i', $url)) {
                $torrent_id = explode('/', $url)[2];
                $urlList[$torrent_id]['id'] = $torrent_id;
                $urlList[$torrent_id]['url'] = $url;
            }
            if (preg_match('/magnet:\?xt=urn:btih:/i', $url)) {
                $urlList[$torrent_id]['magnet'] = $url;
                $urlList[$torrent_id]['description'] = urldecode(explode('&tr', explode('&dn=', $url)[1])[0]);
            }
        }

        return $urlList;
    }

    /**
     * @param array $torrent
     * @return bool
     */
    public static function addTorrent($torrent)
    {
        $stmt = Database::$db->prepare("INSERT IGNORE INTO magnets SET url = ?, magnet = ?, description = ?");
        $stmt->bind_param('sss', $torrent['url'], $torrent['magnet'], $torrent['description']);

        return $stmt->execute();
    }
}