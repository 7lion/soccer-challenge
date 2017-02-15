<?php

namespace AppBundle\Service\Team;

use AppBundle\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;

class TeamCollection
{
    /**
     * @var ArrayCollection
     */
    protected $collection;

    public function __construct(ArrayCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param string $name
     *
     * @return Team|null
     */
    public function findByName($name)
    {
        $result = $this->collection->filter(function (Team $team) use ($name) {
            return $team->getName() === $name;
        });

        if ($result->count() === 0) {
            return null;
        }

        return $result->first();
    }
}
