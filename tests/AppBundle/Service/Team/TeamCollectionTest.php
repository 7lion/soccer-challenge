<?php

namespace Tests\AppBundle\Service\Team;

use AppBundle\Entity\Team;
use AppBundle\Service\Team\TeamCollection;
use Doctrine\Common\Collections\ArrayCollection;

class TeamCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers TeamCollection::findByName
     */
    public function testFindTeamByName()
    {
        $arrayCollection = new ArrayCollection();

        $liverpul = new Team('Liverpul');
        $arrayCollection->add(new Team('Arsenal'));
        $arrayCollection->add($liverpul);
        $arrayCollection->add(new Team('Chelsea'));
        $arrayCollection->add(new Team('Everton'));

        $teamCollection = new TeamCollection($arrayCollection);

        $this->assertEquals($liverpul, $teamCollection->findByName('Liverpul'));
    }

    /**
     * @covers TeamCollection::findByName
     */
    public function testFindTeamByNotExistingName()
    {
        $arrayCollection = new ArrayCollection();

        $arrayCollection->add(new Team('Arsenal'));
        $arrayCollection->add(new Team('Everton'));

        $teamCollection = new TeamCollection($arrayCollection);

        $this->assertEquals(null, $teamCollection->findByName('Liverpul'));
    }
}