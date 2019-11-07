<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PlayController
 * @package App\Controller
 */
class PlayController extends AbstractController
{
    /**
     * @Route("/play", name="play")
     *
     * @return Response
     */
    public function play(): Response
    {
        return $this->render("play.html.twig", ["pageid" => "page-play"]);
    }
}