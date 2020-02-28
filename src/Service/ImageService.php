<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class ImageService
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FileUploader
     */
    private $fileUploader;

    public function __construct(EntityManagerInterface $entityManager,
                                SessionInterface $session,
                                RouterInterface $router, FileUploader $fileUploader)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->router = $router;
        $this->fileUploader = $fileUploader;
    }

    public function addImage(Image $image, Trick $trick, $imageFile, $mainImage)
    {
        if ($imageFile) {
            $imageFileName = $this->fileUploader->upload($imageFile);
            $image->setName($imageFileName);
        }

        if ($mainImage) {
            foreach ($trick->getImages() as $trick_image) {
                if ($trick_image->getMainImg()) {
                    $trick_image->setMainImg(0);
                }
            }
        }

        $image->setIdTrick($trick);
        $image->setCreatedAt(new \DateTime());

        $this->entityManager->persist($image);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'Image ajoutée');
        $response = new RedirectResponse($this->router->generate('trick.edit', ['id' => $trick->getId()]));
        return $response->send();
    }

    public function deleteImage(Image $image)
    {
        $filesystem = new Filesystem();
        $imagePath = 'images/tricks/'.$image->getName();

        if ($filesystem->exists($imagePath)) {
            $filesystem->remove($imagePath);
        }
        $this->entityManager->remove($image);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('success', 'Image supprimée');
    }
}
