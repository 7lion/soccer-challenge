<?php

namespace AppBundle\Service\Crawler;

use AppBundle\DTO\API\MatchDTO;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class CrawlerService
{
    use CrawlerTrait;

    /**
     * @param string $html
     *
     * @return MatchDTO[]
     */
    public function crawl($html)
    {
        $document = new DomCrawler();
        $document->addHtmlContent($html);

        $dtoList = [];

        $document->filter('table.matches tbody tr')->each(function (DomCrawler $dom) use (&$dtoList) {
            $dto = new MatchDTO();

            if (self::exitElement('.date span', $dom)) {
                $timestamp = $dom->filter('.date span')->attr('data-value');
                if ($timestamp) {
                    $dateTime = new \DateTime();
                    $dateTime->setTimestamp($timestamp);
                    $dto->dateTimeOfMatch = $dateTime;
                }
            }

            if (self::exitElement('.team.team-a a', $dom)) {
                $dto->homeTeamName = $dom->filter('.team.team-a a')->attr('title');
            }

            if (self::exitElement('.team.team-b a', $dom)) {
                $dto->awayTeamName = $dom->filter('.team.team-b a')->attr('title');
            }

            if ($scoreString = self::getText('.score-time.score', $dom)) {
                $scoreList = explode(' - ', $scoreString);
                if (count($scoreList) === 2) {
                    list($homeTeamScore, $awayTeamScore) = $scoreList;
                    $dto->homeTeamScore = (int) $homeTeamScore;
                    $dto->awayTeamScore = (int) $awayTeamScore;
                }
            }

            $dtoList[] = $dto;
        });

        return $dtoList;
    }
}
