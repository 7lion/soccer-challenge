<?php

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\DBAL\Connection;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    use KernelDictionary;

    private $tables = [
        \AppBundle\Entity\Match::TABLE_NAME,
        \AppBundle\Entity\Team::TABLE_NAME,
    ];

    /** @BeforeScenario */
    public function before(BeforeScenarioScope $scope)
    {
        $this->clearDb();
    }

    private function clearDb()
    {
        /** @var Connection $connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();
        $connection->beginTransaction();
        foreach ($this->tables as $table) {
            $connection->executeQuery(sprintf('DELETE FROM %s', $table));
            $connection->executeQuery(sprintf('ALTER TABLE %s AUTO_INCREMENT = 1', $table));
        }
        $connection->commit();
    }
}
