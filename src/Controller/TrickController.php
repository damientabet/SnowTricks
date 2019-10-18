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
        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("trick/add", name="trick.add")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function add(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $trick->setCreatedAt(new \DateTime());
            $entityManager->persist($trick);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Figure bien ajouté');

            return $this->redirectToRoute('profile');
        }

        return $this->render('trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("trick/edit/{id}", name="trick.edit")
     * @param Trick $trick
     * @param Request $request
     * @return Response
     */
    public function edit(Trick $trick, Request $request)
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Figure N°'.$trick->getId().' modifiée');

            return $this->redirectToRoute('profile');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }
}
