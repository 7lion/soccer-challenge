<?php

namespace AppBundle\Service\Crawler;

use AppBundle\Assembler\MatchAssembler;
use AppBundle\Service\Client\ClientService;
use Doctrine\ORM\EntityManager;

class CrawlerProcessManager
{
    const MAX_WAITING_SECONDS = 6;

    /**
     * @var ClientService
     */
    private $client;

    /**
     * @var ResponseAnalyzer
     */
    private $responseAnalyzer;

    /**
     * @var CrawlerService
     */
    private $crawlerService;

    /**
     * @var MatchAssembler
     */
    private $matchAssembler;

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(
        ClientService $client,
        ResponseAnalyzer $responseAnalyzer,
        CrawlerService $crawlerService,
        MatchAssembler $matchAssembler,
        EntityManager $em
    ) {
        $this->client = $client;
        $this->responseAnalyzer = $responseAnalyzer;
        $this->crawlerService = $crawlerService;
        $this->matchAssembler = $matchAssembler;
        $this->em = $em;
    }

    /**
     * @param int $pageNumber
     *
     * @return CrawlerProcessResponse
     */
    public function process($pageNumber)
    {
        $html = $this->client->getContentByUrl(UrlBuilder::build($pageNumber));
        $response = $this->responseAnalyzer->parseHtmlResponse($html);
        $content = $this->responseAnalyzer->getHtml($response);
        if (!$content) {
            return new CrawlerProcessResponse(
                CrawlerProcessResponse::STATUS_ERROR,
                $pageNumber,
                'Can\'t find parameters html content'
            );
        }

        $matchDTOList = $this->crawlerService->crawl($content);
        $matchEntityList = $this->matchAssembler->convertManyToEntities($matchDTOList);

        foreach ($matchEntityList as $matchEntity) {
            $this->em->persist($matchEntity);
        }
        $this->em->flush();

        if ($this->responseAnalyzer->isLastPage($response)) {
            return new CrawlerProcessResponse(CrawlerProcessResponse::STATUS_FINISHED, $pageNumber);
        }

        $waitingInSeconds = random_int(2, self::MAX_WAITING_SECONDS);

        return new CrawlerProcessResponse(
            CrawlerProcessResponse::STATUS_HAS_NEXT,
            $pageNumber,
            sprintf('Crawled %d match items', count($matchEntityList)),
            UrlBuilder::getNextPage($pageNumber),
            $waitingInSeconds
        );
    }
}
