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
        $firstTricks = $trickRepository->findFourArticles();
        $lastTricks = $trickRepository->findLastArticles();
        return $this->render('front/index.html.twig', [
            'firstTricks' => $firstTricks,
            'lastTricks' => $lastTricks
        ]);
    }
}
