<?php declare(strict_types=1);


namespace App\Service;


use App\Exception\BadCodeException;
use App\Exception\DuplicateScoreEntryException;
use App\Model\DecodedScore;
use App\Model\Entity\ScoreEntry;
use App\Model\Highscore;
use App\Model\LevelStatistics;
use App\Repository\ScoreEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

/**
 * Class ScoreService
 * @package App\Service
 */
class ScoreService
{
    const NUMBER_LEVELS = 100;

    /** @var ScoreCodecInterface */
    private $scoreCodec;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ScoreEntryRepository */
    private $scoreEntryRepository;

    /** @var bool */
    private $autoflush = true;

    /**
     * ScoreService constructor.
     * @param ScoreCodecInterface $scoreCodec
     * @param EntityManagerInterface $entityManager
     * @param ScoreEntryRepository $scoreEntryRepository
     */
    public function __construct(ScoreCodecInterface $scoreCodec, EntityManagerInterface $entityManager, ScoreEntryRepository $scoreEntryRepository)
    {
        $this->scoreCodec = $scoreCodec;
        $this->entityManager = $entityManager;
        $this->scoreEntryRepository = $scoreEntryRepository;
    }

    /**
     * @param string $code
     * @param string $nick
     * @param string|null $session
     * @param string|null $ip
     *
     * @return Highscore
     * @throws BadCodeException
     * @throws DuplicateScoreEntryException
     * @throws NonUniqueResultException
     */
    public function registerCode(string $code, string $nick, string $session = null, string $ip = null): Highscore
    {
        /** @var  $score DecodedScore */
        $score = $this->scoreCodec->decode($code);

        if ($this->scoreEntryRepository->findOneBy(["code" => $code, "nick" => $nick])) {
            throw new DuplicateScoreEntryException();
        }

        $entry = (new ScoreEntry())
            ->setLevel($score->getLevel())
            ->setMoves($score->getMoves())
            ->setSeconds($score->getSeconds())
            ->setNick($nick)
            ->setCode($code)
            ->setTimestamp(time())
            ->setSession($session)
            ->setIp($ip);

        $this->entityManager->persist($entry);

        /** @var Highscore $highscore */
        $highscore = Highscore::fromScoreEntry($entry);

        if ($this->autoflush) {
            $this->entityManager->flush();
            $highscore->setRank($this->scoreEntryRepository->getRank($entry));
        }

        return $highscore;
    }

    /**
     * @return LevelStatistics[]
     * @throws Exception
     */
    public function levelStatistics(): array
    {
        /** @var LevelStatistics[] $statistics */
        $statistics = [];

        for ($i = 0; $i < self::NUMBER_LEVELS; $i++) {
            $statistics[$i] = new LevelStatistics(0);
        }

        foreach ($this->scoreEntryRepository->getStatistics() as $row) {
            $statistics[$row["level"]]
                ->setPlayedCount(intval($row["count"]))
                ->setBestScore(
                    (new Highscore())
                        ->setNick($row["nick"])
                        ->setMoves(intval($row["moves"]))
                        ->setSeconds(intval($row["seconds"]))
                        ->setTimestamp(intval($row["timestamp"]))
                        ->setLevel(intval($row["level"]))
                        ->setRank(1)
                );
        }

        return $statistics;
    }

    /**
     * @param int $level
     * @return Highscore[]
     * @throws Exception
     */
    public function highScoresForLevel(int $level): array
    {
        /** @var Highscore[] $highscores */
        $highscores = array_map('App\Model\Highscore::fromScoreEntry', $this->scoreEntryRepository->getSortedScoresByLevel($level));

        /** @var int $rank */
        $rank = 1;

        foreach ($highscores as $highscore) {
            $highscore->setRank($rank++);
        }

        return $highscores;
    }

    /**
     * @param bool $autoflush
     */
    public function setAutoflush(bool $autoflush): void
    {
        $this->autoflush = $autoflush;
    }
}
