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
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminTrickController extends AbstractController
{
    public $user;
    public $imageController;

    public function __construct(AuthenticationUtils $authenticationUtils, UserRepository $userRepository, AdminImageController $adminImageController)
    {
        $this->user = $userRepository->findOneBy(['email' => $authenticationUtils->getLastUsername()]);
        $this->imageController = $adminImageController;
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
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Figure N°' . $trick->getId() . ' modifiée');

            return $this->redirectToRoute('trick.edit', ['id' => $trick->getId()]);
        }

        return $this->render('admin/trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/trick/delete/{id}", name="trick.delete")
     * @param Trick $trick
     * @return void
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
        die('ok');
    }
}
