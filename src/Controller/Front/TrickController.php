<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TrickController extends AbstractController
{
    public $user;

    public function __construct(AuthenticationUtils $authenticationUtils, UserRepository $userRepository)
    {
        $this->user = $userRepository->findOneBy([
            'email' => $authenticationUtils->getLastUsername()
        ]);
    }

    /**
     * @Route("trick/{id}-{slug}", name="trick.show", requirements={"slug": "[a-z0-9\-]*" })
     * @param Trick $trick
     * @param string $slug
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function show(Trick $trick, string $slug, Request $request)
    {
        if ($trick->getSlug() !== $slug) {
            $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ], 301);
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $comment->setIdTrick($trick);
            $comment->setIdUser($this->user);
            $comment->setCreatedAt(new \DateTime());

            $entityManager->persist($comment);
            $entityManager->flush();

            // do anything else you need here, like send an email
            //$this->addFlash('success', 'Figure N°' . $trick->getId() . ' modifiée');

            return $this->redirectToRoute('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }

        return $this->render('front/trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'comments' => $trick->getComments()
        ]);
    }
}
