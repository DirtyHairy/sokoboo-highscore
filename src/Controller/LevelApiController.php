<?php


namespace App\Controller;


use App\Service\LevelDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api")
 */
class LevelApiController extends AbstractController
{
    /** @var LevelDataProvider */
    private $levelDataProvider;

    /**
     * LevelApiController constructor.
     * @param LevelDataProvider $levelDataProvider
     */
    public function __construct(LevelDataProvider $levelDataProvider)
    {
        $this->levelDataProvider = $levelDataProvider;
    }

    /**
     * @Route("/statistics", name="statistics")
     *
     * @return Response
     */
    public function levelStatistics(): Response
    {
        return $this->json($this->levelDataProvider->levelStatistics());
    }

    /**
     * @Route("/level/{level<\d+>}/highscore", name="highscore")
     *
     * @param int $level
     * @return Response
     */
    public function highScoresFoRLevel(int $level): Response
    {
        return $this->json($this->levelDataProvider->highScoresForLevel($level));
    }
}