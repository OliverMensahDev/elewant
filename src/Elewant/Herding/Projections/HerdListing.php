<?php

declare(strict_types=1);

namespace Elewant\Herding\Projections;

use Doctrine\DBAL\Connection;

final class HerdListing
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        return $this->connection->fetchAll(sprintf('SELECT * FROM %s', HerdProjector::TABLE_HERD));
    }

    public function findById($herdId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdProjector::TABLE_HERD)
            ->where('herd_id = :herdId')
            ->setParameter('herdId', $herdId);

        return $qb->execute()->fetch();
    }

    public function findElePHPantsByHerdId($herdId) : array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdProjector::TABLE_ELEPHPANT)
            ->where('herd_id = :herdId')
            ->setParameter('herdId', $herdId);

        return $qb->execute()->fetchAll();
    }

}