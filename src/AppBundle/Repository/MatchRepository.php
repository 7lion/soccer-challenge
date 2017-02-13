<?php

namespace AppBundle\Repository;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;

class MatchRepository extends EntityRepository
{
    /**
     * @param int[] $homeTeamIds
     * @param \DateTime|null $fromDate
     * @param \DateTime|null $toDate
     *
     * @return array
     */
    public function getHomeTeamsSummaryData(
        array $homeTeamIds = [],
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        $qb = $this->createQueryBuilder('m');
        $qb
            ->select('homeTeam.id teamId', 'homeTeam.name as teamName')
            ->addSelect('
              SUM(
                CASE
                    WHEN m.homeTeamScore < m.awayTeamScore THEN 0
                    WHEN m.homeTeamScore = m.awayTeamScore THEN 1
                    WHEN m.homeTeamScore > m.awayTeamScore THEN 3
                    ELSE 0
                END
              ) AS score'
            )
            ->addSelect('
              SUM(
                CASE
                    WHEN m.homeTeamScore < m.awayTeamScore THEN 0
                    WHEN m.homeTeamScore > m.awayTeamScore THEN 1
                    ELSE 0
                END
              ) AS wins'
            )
            ->addSelect('
              SUM(
                CASE
                    WHEN m.homeTeamScore < m.awayTeamScore THEN 1
                    WHEN m.homeTeamScore > m.awayTeamScore THEN 0
                    ELSE 0
                END
              ) AS losses'
            )
            ->addSelect('
              SUM(
                CASE
                    WHEN m.homeTeamScore = m.awayTeamScore THEN 1
                    ELSE 0
                END
              ) AS draws'
            )
            ->addSelect('COUNT(m.id) AS played')
            ->join('m.homeTeam', 'homeTeam')
            ->groupBy('teamId');

        if (count($homeTeamIds) > 0) {
            $qb->where(
                $qb->expr()->in('m.homeTeam', $homeTeamIds)
            );
        }

        if ($fromDate) {
            $qb->andWhere($qb->expr()->gt('m.dateTimeOfMatch', ':dateFrom'))
                ->setParameter(':dateFrom', $fromDate, Type::DATE);
        }

        if ($toDate) {
            $qb->andWhere($qb->expr()->lt('m.dateTimeOfMatch', ':dateTo'))
                ->setParameter(':dateTo', $toDate, Type::DATE);
        }

        return $qb->getQuery()->execute();
    }

    /**
     * @param int[] $awayTeamIds
     * @param \DateTime|null $fromDate
     * @param \DateTime|null $toDate
     *
     * @return array
     */
    public function getAwayTeamsSummaryData(
        array $awayTeamIds = [],
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        $qb = $this->createQueryBuilder('m');
        $qb
            ->select('awayTeam.id teamId', 'awayTeam.name as teamName')
            ->addSelect('
              SUM(
                CASE
                    WHEN m.awayTeamScore < m.homeTeamScore THEN 0
                    WHEN m.awayTeamScore = m.homeTeamScore THEN 1
                    WHEN m.awayTeamScore > m.homeTeamScore THEN 3
                    ELSE 0
                END
              ) AS score'
            )
            ->addSelect('
              SUM(
                CASE
                    WHEN m.awayTeamScore < m.homeTeamScore THEN 0
                    WHEN m.awayTeamScore > m.homeTeamScore THEN 1
                    ELSE 0
                END
              ) AS wins'
            )
            ->addSelect('
              SUM(
                CASE
                    WHEN m.awayTeamScore < m.homeTeamScore THEN 1
                    WHEN m.awayTeamScore > m.homeTeamScore THEN 0
                    ELSE 0
                END
              ) AS losses'
            )
            ->addSelect('
              SUM(
                CASE
                    WHEN m.homeTeamScore = m.awayTeamScore THEN 1
                    ELSE 0
                END
              ) AS draws'
            )
            ->addSelect('COUNT(m.id) AS played')
            ->join('m.awayTeam', 'awayTeam')
            ->groupBy('teamId');

        if (count($awayTeamIds) > 0) {
            $qb->where(
                $qb->expr()->in('m.awayTeam', $awayTeamIds)
            );
        }

        if ($fromDate) {
            $qb->andWhere($qb->expr()->gt('m.dateTimeOfMatch', ':dateFrom'))
                ->setParameter(':dateFrom', $fromDate, Type::DATE);
        }

        if ($toDate) {
            $qb->andWhere($qb->expr()->lt('m.dateTimeOfMatch', ':dateTo'))
                ->setParameter(':dateTo', $toDate, Type::DATE);
        }

        return $qb->getQuery()->execute();
    }
}
