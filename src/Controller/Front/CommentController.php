<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("comment/edit/{id}", name="comment.edit")
     * @param Comment $comment
     * @param TrickRepository $trickRepository
     * @param Request $request
     * @return Response
     */
    public function edit(Comment $comment, TrickRepository $trickRepository, Request $request)
    {
        $user = $this->getUser();
        $id_trick = $comment->getIdTrick();
        $trick = $trickRepository->findOneBy(['id' => $id_trick]);

        if ($user->getId() !== $comment->getIdUser()->getId()) {
            return $this->redirectToRoute('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }
        
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire à été modifié');

            return $this->redirectToRoute('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }

        return $this->render('front/trick/comment/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("comment/delete/{id}", name="comment.delete")
     * @param Comment $comment
     * @param TrickRepository $trickRepository
     * @return RedirectResponse
     */
    public function delete(Comment $comment, TrickRepository $trickRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $id_trick = $comment->getIdTrick();
        $trick = $trickRepository->findOneBy(['id' => $id_trick]);

        $entityManager->remove($comment);
        $entityManager->flush();
        $this->addFlash('success', 'Votre commentaire à été supprimé');
        return $this->redirectToRoute('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
    }
}
