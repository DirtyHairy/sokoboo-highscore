<?php


namespace App\Controller;


use App\Exception\BadCodeException;
use App\Exception\DuplicateScoreEntryException;
use App\Service\ScoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HighscoreController
 * @package App\Controller
 */
class HighscoreController extends AbstractController
{
    /** @var ScoreService */
    private $scoreService;

    /**
     * HighscoreController constructor.
     * @param ScoreService $scoreService
     */
    public function __construct(ScoreService $scoreService)
    {
        $this->scoreService = $scoreService;
    }

    /**
     * @return Response
     * @Route("/highscores/{level<\d+>}", name="highscores-for-level")
     * @Route("/highscores", name="highscores")
     */
    public function highscores(): Response
    {
        return $this->render("highscores.html.twig", ["pageid" => "page-highscores"]);
    }

    /**
     * @Route("/new-score", name="newscore", methods={"GET"})
     *
     * @return Response
     */
    public function newScore(): Response
    {
        /** @var string[] $templateParameters */
        $templateParameters = [
            "pageid" => "page-newscore",
            "name" => "",
            "code" => "",
            "score" => null,
            "error" => null
        ];

        return $this->render("newscore.html.twig", $templateParameters);
    }

    /**
     * @Route("/new-score", name="newscore-submit", methods={"POST"})
     *
     * @return Response
     * @throws DuplicateScoreEntryException
     */
    public function newScoreSubmit(Request $request): Response
    {
        /** @var string[] $templateParameters */
        $templateParameters = [
            "pageid" => "page-newscore",
            "name" => "",
            "code" => "",
            "score" => null,
            "error" => null,
        ];

        /** @var string $template */
        $template = "newscore.html.twig";

        /** @var  $valid */
        $valid = true;

        /** @var string $name */
        $name = trim($request->request->get("name"));
        $valid = $valid && preg_match("/^[a-zA-Z0-9\.\-_ ]+$/", $name);

        /** @var string $code */
        $code = $request->request->get("code");
        $valid = $valid && preg_match("/^\d{12}$/", $code);

        if (!$valid) {
            return $this->render($template, array_merge($templateParameters, ["code" => $code, "name" => $name]));
        }

        try {
            $score = $this->scoreService->registerCode($code, $name, null, $request->getClientIp());

            return $this->render($template, array_merge($templateParameters, ["score" => $score]));
        } catch (BadCodeException $e) {
            return $this->render($template, array_merge($templateParameters, ["error" => "Invalid Code", "code" => $code, "name" => $name]));
        } catch (DuplicateScoreEntryException $e) {
            return $this->render($template, array_merge($templateParameters, [
                "error" => sprintf("Code %s already registered for %s", $code, $name),
                "code" => $code,
                "name" => $name
            ]));
        }
    }
}