<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TrickController extends AbstractController
{
    public $user;

    public function __construct(AuthenticationUtils $authenticationUtils, UserRepository $userRepository)
    {
        $this->user = $userRepository->findOneBy(['email' => $authenticationUtils->getLastUsername()]);
    }

    /**
     * @Route("trick/{id}-{slug}", name="trick.show", requirements={"id": "[0-9]*", "slug": "[a-z0-9\-]*" })
     * @param Trick $trick
     * @param string $slug
     * @param Request $request
     * @return Response
     * @throws Exception
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

            return $this->redirectToRoute('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }

        return $this->render('front/trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'comments' => $trick->getComments()
        ]);
    }

    /**
     * @Route("trick/add", name="trick.add")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $trick->setIdUser($this->user);
            $trick->setCreatedAt(new \DateTime());
            $trick->setUpdatedAt(new \DateTime());

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Figure bien ajouté');

            return $this->redirectToRoute('trick.edit', ['id' => $trick->getId()]);
        }

        return $this->render('front/trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
}
