<?php

namespace AppBundle\DTO\API;

class MatchDTO
{
    /**
     * @var string
     */
    public $homeTeamName;

    /**
     * @var int
     */
    public $homeTeamScore;

    /**
     * @var string
     */
    public $awayTeamName;

    /**
     * @var string
     */
    public $awayTeamScore;

    /**
     * @var \DateTime|null
     */
    public $dateTimeOfMatch;
}
