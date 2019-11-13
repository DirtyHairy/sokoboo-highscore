<?php declare(strict_types=1);


namespace App\Controller;


use App\Exception\BadCodeException;
use App\Exception\DuplicateScoreEntryException;
use App\Model\CodeSubmission;
use App\Model\Highscore;
use App\Service\ScoreService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterScoreController
 * @package App\Controller
 */
class RegisterScoreController extends AbstractController
{
    const TEMPLATE = "register_score.html.twig";
    const PAGEID = "page-register-score";
    const TPL_ERROR = "error";
    const TPL_SCORE = "score";
    const TPL_SUBMISSION = "submission";

    /** @var ScoreService */
    private $scoreService;

    /** @var ValidatorInterface */
    private $validator;

    /**
     * HighscoreController constructor.
     * @param ScoreService $scoreService
     * @param ValidatorInterface $validator
     */
    public function __construct(ScoreService $scoreService, ValidatorInterface $validator)
    {
        $this->scoreService = $scoreService;
        $this->validator = $validator;
    }

    /**
     * @Route("/new-score", name="register-score", methods={"GET"})
     *
     * @return Response
     */
    public function newScore(): Response
    {
        return $this->render(self::TEMPLATE, ["pageid" => self::PAGEID]);
    }

    /**
     * @Route("/new-score", name="register-score-submit", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function newScoreSubmit(Request $request): Response
    {
        /** @var CodeSubmission $submission */
        $submission = CodeSubmission::fromPost($request);

        /** @var ConstraintViolationListInterface $validations */
        $violations = $this->validator->validate($submission);

        if (count($violations) > 0) {
            return $this->renderError($violations->get(0)->getMessage(), $submission);
        }

        try {
            $score = $this->scoreService->registerCode(
                $submission->getCode(),
                $submission->getNick(),
                null,
                $request->getClientIp()
            );

            return $this->renderSuccess($score);

        } catch (BadCodeException $e) {
            return $this->renderError("Invalid Code", $submission);

        } catch (DuplicateScoreEntryException $e) {
            return $this->renderError(
                sprintf("Code %s already registered for %s", $submission->getCode(), $submission->getNick()),
                $submission
            );
        }
    }

    /**
     * @param string $error
     * @param CodeSubmission $submission
     * @return Response
     */
    private function renderError(string $error, CodeSubmission $submission): Response
    {
        return $this->render(
            self::TEMPLATE,
            ["pageid" => self::PAGEID, self::TPL_ERROR => $error, self::TPL_SUBMISSION => $submission]
        );
    }

    /**
     * @param Highscore $score
     * @return Response
     */
    private function renderSuccess(Highscore $score): Response
    {
        return $this->render(self::TEMPLATE, ["pageid" => self::PAGEID, self::TPL_SCORE => $score]);
    }
}