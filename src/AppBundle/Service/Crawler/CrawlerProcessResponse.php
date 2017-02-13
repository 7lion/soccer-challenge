<?php

namespace AppBundle\Service\Crawler;

class CrawlerProcessResponse
{
    const STATUS_HAS_NEXT = 1;
    const STATUS_ERROR = 2;
    const STATUS_FINISHED = 3;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var int
     */
    protected $pageNumber;

    /**
     * @var int|null
     */
    protected $nextPage;

    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var int|null
     */
    protected $waitingInSeconds;

    public function __construct($status, $pageNumber, $message = null, $nextPage = null, $waitingInSeconds = null)
    {
        $this->status = $status;
        $this->pageNumber = $pageNumber;
        $this->nextPage = $nextPage;
        $this->waitingInSeconds = $waitingInSeconds;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @return int|null
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int|null
     */
    public function getWaitingInSeconds()
    {
        return $this->waitingInSeconds;
    }
}
