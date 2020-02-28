<?php

namespace App\Controller\Front;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\ImageType;
use App\Service\FileUploader;
use App\Service\ImageService;
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
     * @var ImageService
     */
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @Route("trick/{trick}/image/add", name="image.add")
     * @param Request $request
     * @param Trick $trick
     * @return RedirectResponse|Response
     */
    public function add(Request $request, Trick $trick)
    {
        $image = new Image();
        $form_image = $this->createForm(ImageType::class, $image);
        $form_image->handleRequest($request);

        if ($form_image->isSubmitted() && $form_image->isValid()) {
            $imageFile = $form_image['name']->getData();
            $mainImage = $form_image['main_img']->getData();
            $this->imageService->addImage($image, $trick, $imageFile, $mainImage);
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
        $this->imageService->deleteImage($image);
        echo 'ok';
        exit;
    }
}
