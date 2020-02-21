<?php

namespace App\Controller\Front;

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

class ImageController extends AbstractController
{
    /**
     * @Route("trick/{trick}/image/add", name="image.add")
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

            if ($form_image['main_img']->getData()) {
                foreach ($trick->getImages() as $trick_image) {
                    if ($trick_image->getMainImg()) {
                        $trick_image->setMainImg(0);
                    }
                }
            }

            $image->setIdTrick($trick);
            $image->setCreatedAt(new \DateTime());

            $entityManager->persist($image);
            $entityManager->flush();

            $this->addFlash('success', 'Image ajoutée');

            return $this->redirectToRoute('image.add', ['trick' => $trick->getId()]);
        }

        return $this->render('front/trick/image.html.twig', [
            'trickId' => $trick->getId(),
            'images' => $trick->getImages(),
            'formImage' => $form_image->createView(),
        ]);
    }

    /**
     * @Route("trick/image/delete/{id}", name="image.delete")
     * @param Image $image
     * @return string
     */
    public function delete(Image $image)
    {
        $filesystem = new Filesystem();
        $entityManager = $this->getDoctrine()->getManager();
        $imagePath = 'images/tricks/'.$image->getName();

        if ($filesystem->exists($imagePath)) {
            $filesystem->remove($imagePath);
        }
        $entityManager->remove($image);
        $entityManager->flush();
        $this->addFlash('success', 'Image supprimée');
        echo 'ok';
        exit;
    }
}
