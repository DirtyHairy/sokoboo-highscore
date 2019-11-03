<?php


namespace App\Command;


use App\Exception\BadCodeException;
use App\Service\ScoreCodecInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecodeCodeCommand extends Command
{
    const ARGUMENT_CODE = "code";

    protected static $defaultName = "app:decode-code";

    /** @var ScoreCodecInterface */
    private $scoreCodec;

    /**
     * DecodeCodeCommand constructor.
     * @param ScoreCodecInterface $scoreCodec
     */
    public function __construct(ScoreCodecInterface $scoreCodec)
    {
        parent::__construct();
        $this->scoreCodec = $scoreCodec;
    }

    protected function configure(): void
    {
        $this
            ->setDescription("Decode a score code")
            ->setHelp("This command decodes a score code into level, moves and seconds")
            ->addArgument(self::ARGUMENT_CODE, InputArgument::REQUIRED, "highscore code");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        try {
            $decodedScore = $this->scoreCodec->decode($input->getArgument(self::ARGUMENT_CODE));
        } catch (BadCodeException $e) {
            $output->writeln("ERROR: bad code: " . $e->getMessage());
            return;
        }

        $output
            ->writeln(sprintf(
                "level: %s, moves: %s, seconds: %s",
                $decodedScore->getLevel(),
                $decodedScore->getMoves(),
                $decodedScore->getSeconds()
            ));
    }
}