<?php

namespace IcyApril\TPCrawler;

/**
 * Class Content
 * @package IcyApril\TPCrawler
 */
class Content
{
    /**
     * @param string $phrase
     * @param int $max_words
     * @return string
     */
    public static function truncate($phrase, $max_words)
    {
        $phrase_array = explode(' ', $phrase);
        if (count($phrase_array) > $max_words && $max_words > 0) {
            $phrase = implode(' ', array_slice($phrase_array, 0, $max_words)) . '&hellip;';
        }
        return $phrase;
    }
}