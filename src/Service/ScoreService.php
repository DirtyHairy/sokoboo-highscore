<?php


namespace App\Service;


use App\Exception\BadCodeException;
use App\Exception\DuplicateScoreEntryException;
use App\Model\DecodedScore;
use App\Model\Entity\ScoreEntry;
use App\Model\HighScore;
use App\Model\LevelStatistics;
use App\Repository\ScoreEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @return DecodedScore
     * @throws BadCodeException
     * @throws DuplicateScoreEntryException
     */
    public function registerCode(string $code, string $nick, string $session = null, string $ip = null): DecodedScore
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

        if ($this->autoflush) {
            if ($this->autoflush) {
                $this->entityManager->flush();
            }
        }

        return $score;
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
                ->setPlayedCount($row["count"])
                ->setBestScore(
                    (new HighScore())
                        ->setNick($row["nick"])
                        ->setMoves($row["moves"])
                        ->setSeconds($row["seconds"])
                        ->setTimestamp($row["timestamp"])
                );
        }

        return $statistics;
    }

    /**
     * @param int $level
     * @return HighScore[]
     * @throws Exception
     */
    public function highScoresForLevel(int $level): array
    {
        return array_map('App\Model\HighScore::fromScoreEntry', $this->scoreEntryRepository->getSortedScoresByLevel($level));
    }

    /**
     * @param bool $autoflush
     */
    public function setAutoflush(bool $autoflush): void
    {
        $this->autoflush = $autoflush;
    }
}
