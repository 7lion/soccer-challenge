<?php

namespace AppBundle\Assembler;

use AppBundle\DTO\API\MatchDTO;
use AppBundle\Entity\Match;
use AppBundle\Service\Team\TeamService;

class MatchAssembler
{
    /**
     * @var TeamService
     */
    protected $teamService;

    /**
     * @param TeamService $teamService
     */
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * @param MatchDTO[] $matchDTOList
     *
     * @return Match[]
     */
    public function convertManyToEntities(array $matchDTOList)
    {
        if (count($matchDTOList) === 0) {
            return [];
        }

        $teamNames = [];

        array_map(function (MatchDTO $matchDTO) use (&$teamNames) {
            $teamNames[] = $matchDTO->awayTeamName;
            $teamNames[] = $matchDTO->homeTeamName;
        }, $matchDTOList);

        $teamNames = array_unique($teamNames);
        $teams = $this->teamService->findOrCreateByNames($teamNames);

        $entityList = [];
        foreach ($matchDTOList as $matchDTO) {
            $entity = new Match();

            $entity->setHomeTeam($teams->findByName($matchDTO->homeTeamName));
            $entity->setAwayTeam($teams->findByName($matchDTO->awayTeamName));

            $entity->setHomeTeamScore($matchDTO->homeTeamScore);
            $entity->setAwayTeamScore($matchDTO->awayTeamScore);
            $entity->setDateTimeOfMatch($matchDTO->dateTimeOfMatch);

            $entityList[] = $entity;
        }

        return $entityList;
    }
}
