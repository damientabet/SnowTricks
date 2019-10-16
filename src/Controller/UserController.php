<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("my-profile", name="profile")
     * @return Response
     */
    public function index()
    {
        return $this->render('user/my-profile.html.twig');
    }
}
