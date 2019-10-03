<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/create", name="create-user")
     *
     * @return Response
     */
    public function newUser(): Response
    {
        return $this->render("createuser.html.twig", ["pageid" => "page-login"]);
    }
}