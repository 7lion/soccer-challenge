<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Team;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;

class TeamContext implements Context
{
    use KernelDictionary;

    /**
     * @Given There are teams:
     */
    public function thereAreTeams(TableNode $table)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach ($table->getColumnsHash() as $table) {
            $team = new Team($table['name']);
            $team->setId($table['id']);

            $em->persist($team);
        }

        $em->flush();
    }
}
