<?php declare(strict_types=1);

namespace App\Repository;

use App\Model\Entity\ScoreEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\NonUniqueResultException;
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
     * @return string[]
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
            "
                SELECT
                    level,
                    COUNT(id) `count`,
                    $subqueryMoves moves,
                    $subquerySeconds seconds,
                    $subqueryTimestamp timestamp,
                    $subqueryNick nick
                FROM score_entry se 
                GROUP BY level
            ",
            $rsm
        );

        return $query->getArrayResult();
    }

    /**
     * @param int $level
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getSortedScoresByLevel(int $level, int $offset = 0, int $limit = 20): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select("se")
            ->from("App:ScoreEntry", "se")
            ->where("se.level = :level")
            ->add("orderBy", ["se.moves ASC, se.seconds ASC, se.timestamp ASC"])
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->setParameter("level", $level)
            ->getResult();
    }

    /**
     * @param ScoreEntry $scoreEntry
     * @return int
     * @throws NonUniqueResultException
     */
    public function getRank(ScoreEntry $scoreEntry): int
    {
        $result = $this->entityManager->createQueryBuilder()
            ->select("COUNT(se)")
            ->from("App:ScoreEntry", "se")
            ->where("se.moves <= :moves")
            ->andWhere("se.seconds <= :seconds")
            ->andWhere("se.timestamp <= :timestamp")
            ->andWhere("se.level = :level")
            ->setParameters([
                "moves" => $scoreEntry->getMoves(),
                "seconds" => $scoreEntry->getSeconds(),
                "timestamp" => $scoreEntry->getTimestamp(),
                "level" => $scoreEntry->getLevel()
            ])
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

        return intval($result);
    }
}
