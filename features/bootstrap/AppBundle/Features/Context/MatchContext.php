<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Match;
use AppBundle\Entity\Team;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;

class MatchContext implements Context
{
    use KernelDictionary;

    /**
     * @Given There are matches:
     */
    public function thereAreMatches(TableNode $table)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $teamRepo = $em->getRepository(Team::class);
        foreach ($table->getColumnsHash() as $table) {
            $match = new Match();

            if ($homeTeam = $teamRepo->find($table['homeTeamId'])) {
                $match->setHomeTeam($homeTeam);
            }
            if ($awayTeam = $teamRepo->find($table['awayTeamId'])) {
                $match->setAwayTeam($awayTeam);
            }

            $match->setHomeTeamScore($table['homeTeamScore']);
            $match->setAwayTeamScore($table['awayTeamScore']);

            $dateTime = new \DateTime($table['dateTimeOfMatch']);
            $match->setDateTimeOfMatch($dateTime);

            $em->persist($match);
        }

        $em->flush();
    }
}
