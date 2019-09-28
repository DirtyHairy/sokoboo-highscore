<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HighscoreController
{
    /**
     * @return Response
     *
     * @Route("/", name="sokoboo_index")
     */
    public function index(): Response {
        return new Response('<html><head></head><body>Hello world</body></html>');
    }
}