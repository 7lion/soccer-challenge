<?php

namespace Tests\AppBundle\Service\Crawler;

use AppBundle\DTO\API\MatchDTO;
use AppBundle\Service\Crawler\CrawlerService;

class CrawlerServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers CrawlerService::crawl
     */
    public function testCrawlNotEmptyHtml()
    {
        $crawler = new CrawlerService();

        $html = file_get_contents(__DIR__ . '/mock/matchPage.html');
        $dtoList = $crawler->crawl($html);

        $expected = [];
        $match1 = new MatchDTO();
        $match1->homeTeamName = 'Sunderland';
        $match1->homeTeamScore = 0;
        $match1->awayTeamName = 'Manchester United';
        $match1->awayTeamScore = 1;
        $match1->dateTimeOfMatch = new \DateTime('2012-05-13 14:00:00');

        $expected[] = $match1;

        $match2 = new MatchDTO();
        $match2->homeTeamName = 'Stoke City';
        $match2->homeTeamScore = 2;
        $match2->awayTeamName = 'Bolton Wanderers';
        $match2->awayTeamScore = 2;
        $match2->dateTimeOfMatch = new \DateTime('2012-05-13 14:00:00');

        $expected[] = $match2;

        $this->assertEquals($expected, $dtoList);
    }

    /**
     * @covers CrawlerService::crawl
     */
    public function testCrawlEmptyHtml()
    {
        $crawler = new CrawlerService();

        $html = '';
        $dtoList = $crawler->crawl($html);

        $this->assertEquals([], $dtoList);
    }
}