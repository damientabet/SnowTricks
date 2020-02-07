<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
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
     * @param CommentRepository $commentRepository
     * @return Response
     * @throws Exception
     */
    public function show(Trick $trick, string $slug, Request $request, CommentRepository $commentRepository)
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
            'first_comments' => $commentRepository->findFourComments($trick->getId()),
            'last_comments' => $commentRepository->findLastComments($trick->getId())
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
     * @Route("trick/edit/{id}", name="trick.edit")
     * @param Trick $trick
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function edit(Trick $trick, Request $request, FileUploader $fileUploader)
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Figure N°' . $trick->getId() . ' modifiée');

            return $this->redirectToRoute('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }

        return $this->render('front/trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("trick/delete/{id}", name="trick.delete")
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function delete(Trick $trick)
    {
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($trick->getVideos() as $video) {
            $entityManager->remove($video);
        }

        foreach ($trick->getImages() as $image) {
            $filesystem = new Filesystem();
            $imagePath = 'images/tricks/'.$image->getName();

            if ($filesystem->exists($imagePath)) {
                $filesystem->remove($imagePath);
            }
            $entityManager->remove($image);
        }

        foreach ($trick->getComments() as $comment) {
            $entityManager->remove($comment);
        }

        $entityManager->remove($trick);
        $entityManager->flush();
        $this->addFlash('success', 'Figure N°' . $trick->getId() . ' à été supprimé');

        return $this->redirectToRoute('home');
    }
}
