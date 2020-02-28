<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @var CommentService
     */
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @Route("comment/edit/{id}", name="comment.edit")
     * @param Comment $comment
     * @param TrickRepository $trickRepository
     * @param Request $request
     * @return Response
     */
    public function edit(Comment $comment, TrickRepository $trickRepository, Request $request)
    {
        $trick = $trickRepository->findOneBy(['id' => $comment->getIdTrick()]);

        if ($this->denyAccessUnlessGranted('ROLE_ADMIN') && $this->getUser()->getId() != $comment->getIdUser()->getId()) {
            return $this->redirectToRoute('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }
        
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->editComment($comment, $trick);
        }

        return $this->render('front/trick/comment/edit.html.twig', [
            'form' => $form->createView(),
            'idTrick' => $comment->getIdTrick(),
            'slug' => $trick->getSlug()
        ]);
    }

    /**
     * @Route("comment/delete/{id}", name="comment.delete")
     * @param Comment $comment
     * @param TrickRepository $trickRepository
     * @return void
     */
    public function delete(Comment $comment, TrickRepository $trickRepository)
    {
        $trick = $trickRepository->findOneBy(['id' => $comment->getIdTrick()]);

        $this->commentService->deleteComment($comment, $trick);
    }
}
