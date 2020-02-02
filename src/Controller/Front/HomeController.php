<?php

namespace App\Controller\Front;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository)
    {
        $first_tricks = $trickRepository->findFourArticles();
        $last_tricks = $trickRepository->findLastArticles();
        return $this->render('front/index.html.twig', [
            'first_tricks' => $first_tricks,
            'last_tricks' => $last_tricks
        ]);
    }
}
