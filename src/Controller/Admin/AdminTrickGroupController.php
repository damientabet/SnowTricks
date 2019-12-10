<?php

namespace App\Controller\Admin;

use App\Entity\TrickGroup;
use App\Form\TrickGroupType;
use App\Repository\TrickGroupRepository;
use App\Repository\TrickRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTrickGroupController extends AbstractController
{
    /**
     * @Route("admin/trick-groups", name="trickGroup.index")
     * @param TrickGroupRepository $trickGroupRepository
     * @return Response
     */
    public function index(TrickGroupRepository $trickGroupRepository)
    {
        $trickGroups = $trickGroupRepository->findAll();
        return $this->render('admin/trickGroup/index.html.twig', [
            'trickGroups' => $trickGroups
        ]);
    }

    /**
     * @Route("admin/trickGroup/add", name="trickGroup.add")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(Request $request)
    {
        $trickGroup = new TrickGroup();
        $form = $this->createForm(TrickGroupType::class, $trickGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $trickGroup->setCreatedAt(new \DateTime());
            $trickGroup->setUpdatedAt(new \DateTime());

            $entityManager->persist($trickGroup);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Le groupe bien ajouté');

            return $this->redirectToRoute('trickGroup.index');
        }

        return $this->render('admin/trickGroup/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/trickGroup/edit/{id}", name="trickGroup.edit")
     * @param TrickGroup $trickGroup
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function edit(TrickGroup $trickGroup, Request $request)
    {
        $form = $this->createForm(TrickGroupType::class, $trickGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $trickGroup->setUpdatedAt(new \DateTime());

            $entityManager->persist($trickGroup);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Le groupe bien modifié');

            return $this->redirectToRoute('profile');
        }

        return $this->render('admin/trickGroup/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/trickGroup/delete/{id}", name="trickGroup.delete")
     * @param TrickGroup $trickGroup
     * @param TrickRepository $trickRepository
     * @return void
     */
    public function delete(TrickGroup $trickGroup, TrickRepository $trickRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($tricks = $trickRepository->findBy(['id_trick_group' => $trickGroup->getId()])) {
            foreach ($tricks as $trick) {
                $entityManager->remove($trick);
            }
        }

        $entityManager->remove($trickGroup);
        $entityManager->flush();
        $this->addFlash('success', 'Groupe N°' . $trickGroup->getId() . ' à été supprimé');
        die('ok');
    }
}
