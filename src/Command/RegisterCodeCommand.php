<?php


namespace App\Command;


use App\Exception\BadCodeException;
use App\Exception\DuplicateScoreEntryException;
use App\Service\ScoreService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RegisterCodeCommand
 * @package App\Command
 */
class RegisterCodeCommand extends Command
{
    const ARGUMENT_CODE = "code";
    const ARGUMENT_NICK = "nick";

    protected static $defaultName = "app:register-code";

    /** @var ScoreService */
    private $scoreService;

    /**
     * RegisterCodeCommand constructor.
     * @param ScoreService $scoreService
     */
    public function __construct(ScoreService $scoreService)
    {
        parent::__construct();
        $this->scoreService = $scoreService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription("Register a new code")
            ->setHelp("Register and save a new code in the database")
            ->addArgument(self::ARGUMENT_CODE, InputArgument::REQUIRED, "highscore code")
            ->addArgument(self::ARGUMENT_NICK, InputArgument::REQUIRED, "player nick");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var $code string */
        $code = $input->getArgument(self::ARGUMENT_CODE);

        /** @var string $nick */
        $nick = $input->getArgument(self::ARGUMENT_NICK);

        try {
            $this->scoreService->registerCode($code, $nick);

            $output->writeln(sprintf("successfully registered code %s for player '%s'", $code, $nick));
        } catch (BadCodeException $e) {
            $output->writeln(sprintf("ERROR: bad code: %s", $e->getMessage()));
        } catch (DuplicateScoreEntryException $e) {
            $output->writeln(sprintf("ERROR: code %s already registered for player '%s'", $code, $nick));
        }
    }
}