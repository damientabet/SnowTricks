<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("my-profile", name="profile")
     * @return Response
     */
    public function index()
    {
        return $this->render('front/user/my-profile.html.twig');
    }
}
