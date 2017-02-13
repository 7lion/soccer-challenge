<?php

namespace AppBundle\Service\Match;

use AppBundle\DTO\API\StandingsDTO;
use AppBundle\Entity\Match;
use Doctrine\ORM\EntityManager;

class MatchService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param \DateTime|null $fromDate
     * @param \DateTime|null $toDate
     *
     * @return array
     */
    public function getStandings(\DateTime $fromDate = null, \DateTime $toDate = null)
    {
        return $this->getSummaryData($fromDate, $toDate);
    }

    private function getSummaryData(\DateTime $fromDate = null, \DateTime $toDate = null)
    {
        $matchRepo = $this->em->getRepository(Match::class);
        $homeTeamsData = $matchRepo->getHomeTeamsSummaryData([], $fromDate, $toDate);
        $awayTeamsData = $matchRepo->getAwayTeamsSummaryData([], $fromDate, $toDate);

        return $this->mergeHomeAndAwayTeamsSummaryData($homeTeamsData, $awayTeamsData);
    }

    private function mergeHomeAndAwayTeamsSummaryData(array $homeTeamsData, array $awayTeamsData)
    {
        $result = [];

        foreach ($homeTeamsData as $homeTeamData) {
            foreach ($awayTeamsData as $awayTeamKey => $awayTeamData) {
                if ($homeTeamData['teamId'] === $awayTeamData['teamId']) {
                    $standings = new StandingsDTO();
                    $standings->name = $homeTeamData['teamName'];
                    $standings->score = $homeTeamData['score'] + $awayTeamData['score'];
                    $standings->wins = $homeTeamData['wins'] + $awayTeamData['wins'];
                    $standings->losses = $homeTeamData['losses'] + $awayTeamData['losses'];
                    $standings->draws = $homeTeamData['draws'] + $awayTeamData['draws'];
                    $standings->played = $homeTeamData['played'] + $awayTeamData['played'];

                    $result[] = $standings;

                    unset($awayTeamsData[$awayTeamKey]);
                    break;
                }
            }
        }

        foreach ($awayTeamsData as $awayTeamData) {
            $standings = new StandingsDTO();
            $standings->name = $awayTeamData['teamName'];
            $standings->score = $awayTeamData['score'];
            $standings->wins = $awayTeamData['wins'];
            $standings->losses = $awayTeamData['losses'];
            $standings->draws = $awayTeamData['draws'];
            $standings->played = $awayTeamData['played'];
        }

        usort($result, function (StandingsDTO $obj1, StandingsDTO $obj2) {
            if ($obj1->score === $obj2->score) {
                return 0;
            }

            return $obj1->score > $obj2->score ? -1 : 1;
        });

        foreach ($result as $i => $item) {
            /** @var StandingsDTO $item */
            $item->place = ++$i;
        }

        return $result;
    }
}
