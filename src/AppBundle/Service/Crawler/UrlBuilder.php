<?php

namespace AppBundle\Service\Crawler;

class UrlBuilder
{
    const PATTERN_URL = 'http://int.soccerway.com/a/block_competition_matches' .
    '?block_id=page_competition_1_block_competition_matches_7&callback_params='.
    '{"page":0,"bookmaker_urls":{},"block_service_id":"competition_matches_block_competitionmatches",'
    .'"round_id":14829,"outgroup":false,"view":2}&action=changePage&params={"page":%d}';

    const DEFAULT_PAGE = 0;

    public static function build($page = self::DEFAULT_PAGE)
    {
        return sprintf(self::PATTERN_URL, $page);
    }

    public static function getNextPage($currentPage)
    {
        return --$currentPage;
    }
}
