<?php

namespace AppBundle\Service\Crawler;

use Symfony\Component\DomCrawler\Crawler;

trait CrawlerTrait
{
    /**
     * @param string $xpath
     * @param Crawler $dom
     *
     * @return bool
     */
    public static function exitElement($xpath, Crawler $dom)
    {
        return (bool)$dom->filter($xpath)->count();
    }

    /**
     * @param string $xpath
     * @param Crawler $dom
     *
     * @return string
     */
    public static function getText($xpath, Crawler $dom)
    {
        if ($dom->filter($xpath)->count()) {
            return trim($dom->filter($xpath)->text());
        }

        return '';
    }
}
