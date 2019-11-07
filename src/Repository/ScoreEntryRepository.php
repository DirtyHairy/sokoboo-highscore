<?php

namespace App\Repository;

use App\Model\Entity\ScoreEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ScoreEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScoreEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScoreEntry[]    findAll()
 * @method ScoreEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreEntryRepository extends ServiceEntityRepository
{
    /**
     * ScoreEntryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScoreEntry::class);
    }
}
