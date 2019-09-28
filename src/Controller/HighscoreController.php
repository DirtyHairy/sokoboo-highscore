<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HighscoreController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("/", name="sokoboo_index")
     */
    public function index(): Response {
        return $this->render("index.html.twig");
    }
}