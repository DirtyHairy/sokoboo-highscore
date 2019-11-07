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
    /** @var ScoreCodecInterface */
    private $scoreCodec;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ScoreEntryRepository */
    private $scoreEntryRepository;

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
     * @throws BadCodeException
     * @throws DuplicateScoreEntryException
     */
    public function registerCode(string $code, string $nick, string $session = null, string $ip = null): void
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

        $this->entityManager->flush();
    }

    /**
     * @return LevelStatistics[]
     * @throws Exception
     */
    public function levelStatistics(): array
    {
        /** @var LevelStatistics[] $levelStatistics */
        $levelStatistics = [];

        for ($i = 0; $i <= 255; $i++) {
            $highScore = new HighScore(
                "someplayer",
                random_int(10, 50),
                random_int(30, 5400),
                random_int(0, 50 * 24 * 3600 * 365)
            );

            $levelStatistics[$i] = new LevelStatistics(max(0, random_int($i * -5, 5 * (600 - $i))), $highScore);
        }

        return $levelStatistics;
    }

    /**
     * @param int $level
     * @return HighScore[]
     * @throws Exception
     */
    public function highScoresForLevel(int $level): array
    {
        if ($level < 0 || $level > 255) {
            return [];
        }

        /** @var HighScore[] $highScores */
        $highScores = [];

        for ($i = 0; $i < 50; $i++) {
            $highScores[$i] = new HighScore(
                sprintf("player%s", $i + 1),
                random_int(5, 100),
                random_int(30, 5400),
                random_int(0, 50 * 24 * 3600 * 365)
            );
        }

        return $highScores;
    }
}
