<?php


namespace App\Command;


use App\Exception\BadCodeException;
use App\Exception\DuplicateScoreEntryException;
use App\Model\DecodedScore;
use App\Service\ScoreCodecInterface;
use App\Service\ScoreService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateFakeScores
 * @package App\Command
 */
class GenerateFakeScores extends Command
{
    const ARGUMENT_COUNT = "count";

    /** @var string string */
    protected static $defaultName = "app:generate-fake-scores";

    /** @var string[] */
    private static $nicknames = [
        "DirtyHairy",
        "A. Davie",
        "Paul Paddock",
        "Buster Last",
        "Barb Pineapple",
        "Joe Slowpoke",
        "Fred Loser",
        "I. C. Wiener",
        "H. Simpson",
        "Stella",
        "Martha Focker"
    ];

    /** @var ScoreService */
    private $scoreService;

    /** @var ScoreCodecInterface */
    private $scoreCodec;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * GenerateFakeScores constructor.
     * @param ScoreService $scoreService
     * @param ScoreCodecInterface $scoreCodec
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ScoreService $scoreService, ScoreCodecInterface $scoreCodec, EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->scoreService = $scoreService;
        $this->scoreCodec = $scoreCodec;
        $this->entityManager = $entityManager;
    }

    /**
     *
     */
    protected function configure(): void
    {
        $this
            ->setDescription("generate fake scores")
            ->setHelp("Populate the database with fake scores")
            ->addArgument(self::ARGUMENT_COUNT, InputArgument::REQUIRED, "number of genersted score");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws BadCodeException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var string $count */
        $count = $input->getArgument(self::ARGUMENT_COUNT);

        /** @var  $progress */
        $progress = new ProgressBar($output);

        /** @var int $actualCount */
        $actualCount = $this->generateScores($count, $progress);

        $output->writeln(sprintf("\n\ninserted %s random scores into the database", $actualCount));
    }

    /**
     * @param int $count
     * @param ProgressBar $progress
     * @return int
     * @throws BadCodeException
     */
    private function generateScores(int $count, ProgressBar $progress): int
    {
        /** @var int $actualCount */
        $actualCount = 0;

        $this->scoreService->setAutoflush(false);

        /** @var bool[] $codes */
        $codes = [];

        $progress->start($count);
        for ($i = 0; $i < $count; $i++) {
            /** @var string $code */
            $code = $this->scoreCodec->encode(
                new DecodedScore(
                    random_int(0, 99),
                    random_int(100, 200),
                    random_int(3600, 7200)
                )
            );

            if (!empty($codes[$code])) {
                $progress->advance();
                continue;
            }

            $codes[$code] = true;

            $nick = self::$nicknames[random_int(0, count(self::$nicknames) - 1)];

            try {
                $this->scoreService->registerCode($code, $nick);
                $actualCount++;
            } catch (DuplicateScoreEntryException $e) {
            }

            $progress->advance();
        }

        $this->entityManager->flush();
        $progress->finish();

        return $actualCount;
    }
}