<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\ImageType;
use App\Service\FileUploader;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminImageController extends AbstractController
{
    /**
     * @Route("admin/trick/{trick}/image/add", name="image.add")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param Trick $trick
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(Request $request, FileUploader $fileUploader, Trick $trick)
    {
        $image = new Image();
        $form_image = $this->createForm(ImageType::class, $image);
        $form_image->handleRequest($request);

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

            return $this->redirectToRoute('image.add', ['trick' => $trick->getId()]);
        }

        return $this->render('admin/trick/image.html.twig', [
            'trickId' => $trick->getId(),
            'images' => $trick->getImages(),
            'form_image' => $form_image->createView(),
        ]);
    }

    /**
     * @Route("admin/trick/image/delete/{id}", name="image.delete")
     * @param Image $image
     * @return RedirectResponse
     */
    public function delete(Image $image)
    {
        $filesystem = new Filesystem();
        $id_trick = $image->getIdTrick()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $imagePath = 'images/tricks/'.$image->getName();

        if ($filesystem->exists($imagePath)) {
            $filesystem->remove($imagePath);
        }
        $entityManager->remove($image);
        $entityManager->flush();
        //$this->addFlash('success', 'Figure N°' . $trick->getId() . ' à été supprimé');
        return $this->redirectToRoute('trick.edit', ['id' => $id_trick]);
    }
}
