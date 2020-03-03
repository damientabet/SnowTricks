<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Service\CommentService;
use App\Service\TrickService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @var TrickService
     */
    private $trickService;

    public function __construct(TrickService $trickService)
    {
        $this->trickService = $trickService;
    }

    /**
     * @Route("trick/{id}-{slug}", name="trick.show", requirements={"id": "[0-9]*", "slug": "[a-z0-9\-]*" })
     * @param Trick $trick
     * @param string $slug
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @param CommentService $commentService
     * @return Response
     */
    public function show(Trick $trick, string $slug, Request $request, CommentRepository $commentRepository, CommentService $commentService)
    {
        $this->trickService->checkSlug($trick, $slug);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentService->addComment($comment, $trick);
        }

        return $this->render('front/trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'firstComments' => $commentRepository->findFourComments($trick->getId()),
            'lastComments' => $commentRepository->findLastComments($trick->getId())
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
            $this->trickService->addTrick($trick);
        }

        return $this->render('front/trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("trick/edit/{id}", name="trick.edit")
     * @param Trick $trick
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function edit(Trick $trick, Request $request)
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->trickService->editTrick($trick);
        }
        return $this->render('front/trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("trick/delete/{id}", name="trick.delete")
     * @param Trick $trick
     * @return void
     */
    public function delete(Trick $trick)
    {
        $this->trickService->deleteTrick($trick);
    }
}
