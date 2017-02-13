<?php

namespace AppBundle\Service\Team;

use AppBundle\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class TeamService
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
     * @param string[] $teamNames
     *
     * @return TeamCollection
     */
    public function findOrCreateByNames(array $teamNames)
    {
        $teams = $this->em->getRepository(Team::class)->findBy(['name' => $teamNames]);
        $arrayCollection = new ArrayCollection($teams);

        foreach ($teams as $team) {
            foreach ($teamNames as $key => $name) {
                if ($team->getName() === $name) {
                    unset($teamNames[$key]);
                    break;
                }
            }
        }

        if (count($teamNames) > 0) {
            $newTeams = [];
            foreach ($teamNames as $name) {
                $team = new Team($name);
                $this->em->persist($team);

                $newTeams[] = $team;
                $arrayCollection->add($team);
            }

            $this->em->flush($newTeams);
        }

        return new TeamCollection($arrayCollection);
    }
}
