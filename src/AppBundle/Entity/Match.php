<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name=Match::TABLE_NAME)
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MatchRepository")
 */
class Match
{
    const TABLE_NAME = '`match`';

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Team", cascade={"persist"})
     * @ORM\JoinColumn(name="homeTeamId", referencedColumnName="id", nullable=true)
     */
    protected $homeTeam;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $homeTeamScore;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Team", cascade={"persist"})
     * @ORM\JoinColumn(name="awayTeamId", referencedColumnName="id", nullable=true)
     */
    protected $awayTeam;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $awayTeamScore;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateTimeOfMatch;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getHomeTeamScore()
    {
        return $this->homeTeamScore;
    }

    /**
     * @param int $homeTeamScore
     */
    public function setHomeTeamScore($homeTeamScore)
    {
        $this->homeTeamScore = $homeTeamScore;
    }

    /**
     * @return int
     */
    public function getAwayTeamScore()
    {
        return $this->awayTeamScore;
    }

    /**
     * @return mixed
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * @param mixed $homeTeam
     */
    public function setHomeTeam(Team $homeTeam)
    {
        $this->homeTeam = $homeTeam;
    }

    /**
     * @return Team
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * @param Team $awayTeam
     */
    public function setAwayTeam(Team $awayTeam)
    {
        $this->awayTeam = $awayTeam;
    }

    /**
     * @param int $awayTeamScore
     */
    public function setAwayTeamScore($awayTeamScore)
    {
        $this->awayTeamScore = $awayTeamScore;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateTimeOfMatch()
    {
        return $this->dateTimeOfMatch;
    }

    /**
     * @param \DateTime $dateTimeOfMatch
     */
    public function setDateTimeOfMatch($dateTimeOfMatch = null)
    {
        $this->dateTimeOfMatch = $dateTimeOfMatch;
    }
}
