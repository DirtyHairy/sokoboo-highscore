<?php


namespace App\Command;


use App\Exception\BadCodeException;
use App\Exception\DuplicateScoreEntryException;
use App\Model\DecodedScore;
use App\Service\ScoreCodecInterface;
use App\Service\ScoreService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RegisterScoreCommand
 * @package App\Command
 */
class RegisterScoreCommand extends Command
{
    const ARGUMENT_LEVEL = "level";
    const ARGUMENT_MOVES = "moves";
    const ARGUMENT_SECONDS = "seconds";
    const ARGUMENT_NICK = "nick";

    /** @var string */
    protected static $defaultName = "app:register-score";

    /** @var ScoreService */
    private $scoreService;

    /** @var ScoreCodecInterface */
    private $scoreCodec;

    /**
     * RegisterScoreCommand constructor.
     *
     * @param ScoreService $scoreService
     * @param ScoreCodecInterface $scoreCodec
     */
    public function __construct(ScoreService $scoreService, ScoreCodecInterface $scoreCodec)
    {
        parent::__construct();

        $this->scoreService = $scoreService;
        $this->scoreCodec = $scoreCodec;
    }

    /**
     *
     */
    protected function configure(): void
    {
        $this
            ->setDescription("Register a new high score")
            ->setHelp("Register and save a new high score in the database")
            ->addArgument(self::ARGUMENT_LEVEL, InputArgument::REQUIRED, "level")
            ->addArgument(self::ARGUMENT_MOVES, InputArgument::REQUIRED, "moves")
            ->addArgument(self::ARGUMENT_SECONDS, InputArgument::REQUIRED, "seconds")
            ->addArgument(self::ARGUMENT_NICK, InputArgument::REQUIRED, "player nick");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     * @throws BadCodeException
     * @throws DuplicateScoreEntryException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var string $level */
        $level = $input->getArgument(self::ARGUMENT_LEVEL);

        /** @var string $moves */
        $moves = $input->getArgument(self::ARGUMENT_MOVES);

        /** @var string $seconds */
        $seconds = $input->getArgument(self::ARGUMENT_SECONDS);

        /** @var string $nick */
        $nick = $input->getArgument(self::ARGUMENT_NICK);

        /** @var string $code */
        $code = $this->scoreCodec->encode(new DecodedScore($level, $moves, $seconds));

        $this->scoreService->registerCode($code, $nick);

        $output->writeln(sprintf("successfully registered code %s for %s", $code, $nick));
    }
}