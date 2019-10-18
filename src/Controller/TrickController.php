<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/tricks", name="trick.index")
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository)
    {
        $tricks = $trickRepository->findAll();
        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("trick/{id}-{slug}", name="trick.show", requirements={"slug": "[a-z0-9\-]*" })
     * @param Trick $trick
     * @return Response
     */
    public function show(Trick $trick, string $slug)
    {
        if ($trick->getSlug() !== $slug) {
            $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ], 301);
        }
        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }
}
