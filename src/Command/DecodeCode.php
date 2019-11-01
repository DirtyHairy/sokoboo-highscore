<?php


namespace App\Command;


use App\Exception\BadCodeException;
use App\Service\ScoreCodec;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecodeCode extends Command
{
    const ARGUMENT_CODE = "code";


    protected static $defaultName = "app:decode-code";

    /** @var ScoreCodec */
    private $scoreCodec;

    /**
     * DecodeCode constructor.
     * @param ScoreCodec $scoreCodec
     */
    public function __construct(ScoreCodec $scoreCodec)
    {
        parent::__construct();
        $this->scoreCodec = $scoreCodec;
    }

    protected function configure()
    {
        $this
            ->setDescription("Decode a score code")
            ->setHelp("This command decodes a score code into level, moves and seconds")
            ->addArgument(self::ARGUMENT_CODE, InputArgument::REQUIRED, "highscore code");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $decodedScore = $this->scoreCodec->decode($input->getArgument(self::ARGUMENT_CODE));
        } catch (BadCodeException $e) {
            $output->writeln("ERROR: bad code!");
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