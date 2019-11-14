<?php declare(strict_types=1);


namespace App\Controller;


use App\Constants\SessionHandling;
use App\Exception\BadCodeException;
use App\Exception\DuplicateScoreEntryException;
use App\Model\CodeSubmission;
use App\Model\Highscore;
use App\Service\ScoreService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
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
    const TPL_NICK = "nick";

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
     * @param Request $request
     * @return Response
     */
    public function newScore(Request $request): Response
    {
        return $this->render(self::TEMPLATE,
            [
                "pageid" => self::PAGEID,
                self::TPL_NICK => $request->getSession()->get(SessionHandling::SESSION_VARIABLE_NICK)
            ]
        );
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
        /** @var SessionInterface $session */
        $session = $request->getSession();

        if (!$session->get(SessionHandling::SESSION_VARIABLE_AUTHORIZED)) {
            return $this->renderForbidden();
        }

        /** @var CodeSubmission $submission */
        $submission = CodeSubmission::fromPost($request);

        /** @var ConstraintViolationListInterface $validations */
        $violations = $this->validator->validate($submission);

        if ($this->isNickValid($violations)) {
            $session->set(SessionHandling::SESSION_VARIABLE_NICK, $submission->getNick());
        }

        if (count($violations) > 0) {
            return $this->renderError($violations->get(0)->getMessage(), $submission);
        }

        try {
            $score = $this->scoreService->registerCode(
                $submission->getCode(),
                $submission->getNick(),
                $session->get(SessionHandling::SESSION_VARIABLE_ID),
                $request->getClientIp()
            );

            $session->migrate(true);

            return $this->renderSuccess($score, $submission);

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
     * @param ConstraintViolationListInterface $violations
     * @return bool
     */
    private function isNickValid(ConstraintViolationListInterface $violations): bool
    {
        foreach ($violations as $violation) {
            /** @var ConstraintViolation $violation */

            if ($violation->getPropertyPath() == "nick") {
                return false;
            }
        }

        return true;
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
            [
                "pageid" => self::PAGEID,
                self::TPL_ERROR => $error,
                self::TPL_SUBMISSION => $submission,
                self::TPL_NICK => $submission->getNick()
            ]
        );
    }

    /**
     * @param Highscore $score
     * @param CodeSubmission $submission
     * @return Response
     */
    private function renderSuccess(Highscore $score, CodeSubmission $submission): Response
    {
        return $this->render(self::TEMPLATE,
            [
                "pageid" => self::PAGEID,
                self::TPL_SCORE => $score,
                self::TPL_NICK => $submission->getNick()
            ]
        );
    }

    /**
     * @return Response
     */
    private function renderForbidden(): Response
    {
        return $this
            ->render("forbidden.html.twig", ["pageid" => self::PAGEID])
            ->setStatusCode(Response::HTTP_FORBIDDEN);
    }
}