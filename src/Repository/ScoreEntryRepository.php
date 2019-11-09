<?php

namespace App\Repository;

use App\Model\Entity\ScoreEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method ScoreEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScoreEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScoreEntry[]    findAll()
 * @method ScoreEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreEntryRepository extends ServiceEntityRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * ScoreEntryRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, ScoreEntry::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getStatistics(): array
    {
        /** @var string $subquery */
        $subquery = "(SELECT %s from score_entry WHERE level = se.level ORDER BY moves ASC, seconds ASC, timestamp ASC LIMIT 1)";

        /** @var string $subqueryMoves */
        $subqueryMoves = sprintf($subquery, "moves");

        /** @var string $subquerySeconds */
        $subquerySeconds = sprintf($subquery, "seconds");

        /** @var string $subqueryTimestamp */
        $subqueryTimestamp = sprintf($subquery, "timestamp");

        /** @var string $subqueryTimestamp */
        $subqueryNick = sprintf($subquery, "nick");

        /** @var ResultSetMapping $rsm */
        $rsm = (new ResultSetMapping())
            ->addScalarResult("level", "level")
            ->addScalarResult("count", "count")
            ->addScalarResult("moves", "moves")
            ->addScalarResult("seconds", "seconds")
            ->addScalarResult("timestamp", "timestamp")
            ->addScalarResult("nick", "nick");

        /** @var NativeQuery $query */
        $query = $this->entityManager->createNativeQuery(
            "SELECT level, COUNT(id) `count`, $subqueryMoves moves, $subquerySeconds seconds, $subqueryTimestamp timestamp, $subqueryNick nick FROM score_entry se GROUP BY level",
            $rsm
        );

        return $query->getArrayResult();
    }
}
