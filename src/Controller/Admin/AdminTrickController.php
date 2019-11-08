<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\ImageType;
use App\Form\TrickType;
use App\Form\VideoType;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminTrickController extends AbstractController
{
    public $user;

    public function __construct(AuthenticationUtils $authenticationUtils, UserRepository $userRepository)
    {
        $this->user = $userRepository->findOneBy([
            'email' => $authenticationUtils->getLastUsername()
        ]);
    }

    /**
     * @Route("admin/tricks", name="trick.index")
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository)
    {
        $tricks = $trickRepository->findAll();
        return $this->render('admin/trick/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("admin/trick/add", name="trick.add")
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

            $trick->setIdUser($this->user);
            $trick->setCreatedAt(new \DateTime());
            $trick->setUpdatedAt(new \DateTime());

            $entityManager->persist($trick);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Figure bien ajouté');

            return $this->redirectToRoute('profile');
        }

        return $this->render('admin/trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/trick/edit/{id}", name="trick.edit")
     * @param Trick $trick
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function edit(Trick $trick, Request $request, FileUploader $fileUploader)
    {
        $video = new Video();
        $image = new Image();
        $form = $this->createForm(TrickType::class, $trick);
        $form_video = $this->createForm(VideoType::class, $video);
        $form_image = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        $form_video->handleRequest($request);
        $form_image->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Figure N°' . $trick->getId() . ' modifiée');

            return $this->redirectToRoute('trick.edit', ['id' => $trick->getId()]);
        }

        if ($form_video->isSubmitted() && $form_video->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $video->setIdTrick($trick);
            $video->setCreatedAt(new \DateTime());

            $entityManager->persist($video);
            $entityManager->flush();

            // do anything else you need here, like send an email
            //$this->addFlash('success', 'Figure N°' . $trick->getId() . ' modifiée');

            return $this->redirectToRoute('trick.edit', ['id' => $trick->getId()]);
        }

        if ($form_image->isSubmitted() && $form_image->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $imageFile = $form_image['name']->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $image->setName($imageFileName);
            }

            $image->setIdTrick($trick);
            $image->setCreatedAt(new \DateTime());

            $entityManager->persist($image);
            $entityManager->flush();

            // do anything else you need here, like send an email
            //$this->addFlash('success', 'Figure N°' . $trick->getId() . ' modifiée');

            return $this->redirectToRoute('trick.edit', ['id' => $trick->getId()]);
        }

        return $this->render('admin/trick/edit.html.twig', [
            'trick' => $trick,
            'videos' => $trick->getVideos(),
            'images' => $trick->getImages(),
            'form' => $form->createView(),
            'form_video' => $form_video->createView(),
            'form_image' => $form_image->createView(),
        ]);
    }

    /**
     * @Route("admin/trick/delete/{id}", name="trick.delete")
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function delete(Trick $trick)
    {
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($trick->getVideos() as $video) {
            $entityManager->remove($video);
        }

        $entityManager->remove($trick);
        $entityManager->flush();
        $this->addFlash('success', 'Figure N°' . $trick->getId() . ' à été supprimé');
        return $this->redirectToRoute('trick.index');
    }
}
