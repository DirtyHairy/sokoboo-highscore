<?php declare(strict_types=1);


namespace App\Command;


use App\Model\DecodedScore;
use App\Service\ScoreCodecInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EncodeCodeCommand
 * @package App\Command
 */
class EncodeCodeCommand extends Command
{
    const ARGUMENT_LEVEL = "level";
    const ARGUMENT_MOVES = "moves";
    const ARGUMENT_SECONDS = "seconds";

    protected static $defaultName = "app:encode-code";

    /** @var ScoreCodecInterface */
    private $scoreCodec;

    /**
     * EncodeCodeCommand constructor.
     * @param ScoreCodecInterface $scoreCodec
     */
    public function __construct(ScoreCodecInterface $scoreCodec)
    {
        parent::__construct();
        $this->scoreCodec = $scoreCodec;
    }

    protected function configure(): void
    {
        $this->setDescription("Encode a score code")
            ->setHelp("This command encodes a level, the number of moves and the time played into a score code.")
            ->addArgument(self::ARGUMENT_LEVEL, InputArgument::REQUIRED, "level played")
            ->addArgument(self::ARGUMENT_MOVES, InputArgument::REQUIRED, "number of moves")
            ->addArgument(self::ARGUMENT_SECONDS, InputArgument::REQUIRED, "time played in seconds");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var int $level */
        $level = intval($input->getArgument(self::ARGUMENT_LEVEL));

        /** @var int $moves */
        $moves = intval($input->getArgument(self::ARGUMENT_MOVES));

        /** @var int $seconds */
        $seconds = intval($input->getArgument(self::ARGUMENT_SECONDS));

        $output->writeln(sprintf("encoded code: %s", $this->scoreCodec->encode(
            new DecodedScore($level, $moves, $seconds)
        )));
    }
}