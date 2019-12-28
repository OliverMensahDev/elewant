<?php

declare(strict_types=1);

namespace Elewant\Reporting\Infrastructure\Doctrine\Statistics;

use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use Elewant\Reporting\DomainModel\Statistics\NumberOf;

final class NumberOfRepository implements NumberOf
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     * @return int
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function newHerdsFormedBetween(DateTimeInterface $from, DateTimeInterface $to): int
    {
        $dql = <<<'EOQ'
SELECT COUNT(h)
FROM Herding:Herd h
WHERE h.formedOn BETWEEN :from AND :to
EOQ;

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('from', $from->format('Y-m-d 00:00:00'));
        $query->setParameter('to', $to->format('Y-m-d 23:59:59'));

        return (int) $query->getSingleScalarResult();
    }

    /**
     * @return int
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function herdsEverFormed(): int
    {
        $dql = <<<'EOQ'
SELECT COUNT(h)
FROM Herding:Herd h
EOQ;

        $query = $this->entityManager->createQuery($dql);

        return (int) $query->getSingleScalarResult();
    }

    /**
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     * @return int
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function newElePHPantsAdoptedBetween(DateTimeInterface $from, DateTimeInterface $to): int
    {
        $dql = <<<'EOQ'
SELECT COUNT(e)
FROM Herding:ElePHPant e
WHERE e.adoptedOn BETWEEN :from AND :to
EOQ;

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('from', $from->format('Y-m-d 00:00:00'));
        $query->setParameter('to', $to->format('Y-m-d 23:59:59'));

        return (int) $query->getSingleScalarResult();
    }
}
