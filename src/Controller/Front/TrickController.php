<?php

namespace App\Controller\Front;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("trick/{id}-{slug}", name="trick.show", requirements={"slug": "[a-z0-9\-]*" })
     * @param Trick $trick
     * @param string $slug
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
        $form = $this->createForm(CommentType::class, $comment);
        return $this->render('front/trick/show.html.twig', [
            'trick' => $trick
            'form' => $form->createView(),
        ]);
    }
}
