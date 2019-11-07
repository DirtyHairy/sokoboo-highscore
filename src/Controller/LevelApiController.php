<?php


namespace App\Controller;


use App\Service\ScoreService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api")
 */
class LevelApiController extends AbstractController
{
    /** @var ScoreService */
    private $scoreService;

    /**
     * LevelApiController constructor.
     * @param ScoreService $levelDataProvider
     */
    public function __construct(ScoreService $levelDataProvider)
    {
        $this->scoreService = $levelDataProvider;
    }

    /**
     * @Route("/statistics", name="api-statistics")
     *
     * @return Response
     * @throws Exception
     */
    public function levelStatistics(): Response
    {
        return $this->json($this->scoreService->levelStatistics());
    }

    /**
     * @Route("/level/{level<\d+>}/highscore", name="api-highscore")
     *
     * @param int $level
     * @return Response
     * @throws Exception
     */
    public function highScoresFoRLevel(int $level): Response
    {
        return $this->json($this->scoreService->highScoresForLevel($level));
    }
}