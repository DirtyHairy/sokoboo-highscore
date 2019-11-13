<?php declare(strict_types=1);


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HighscoreController
 * @package App\Controller
 */
class HighscoreController extends AbstractController
{
    /**
     * @return Response
     * @Route("/highscores/{level<\d+>}", name="highscores-for-level")
     * @Route("/highscores", name="highscores")
     */
    public function highscores(): Response
    {
        return $this->render("highscores.html.twig", ["pageid" => "page-highscores"]);
    }
}