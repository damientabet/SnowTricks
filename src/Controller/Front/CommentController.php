<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
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
        return $this->redirectToRoute('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
    }
}
