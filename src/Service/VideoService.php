<?php

namespace App\Service;

use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class VideoService
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

    public function __construct(EntityManagerInterface $entityManager,
                                SessionInterface $session,
                                RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->router = $router;
    }

    public function addVideo(Video $video, Trick $trick)
    {
        $video->setIdTrick($trick);

        $this->entityManager->persist($video);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'Vidéo ajoutée');
        $response = new RedirectResponse($this->router->generate('trick.edit', ['id' => $trick->getId()]));
        return $response->send();
    }

    public function deleteVideo(Video $video)
    {
        $this->entityManager->remove($video);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('success', 'Vidéo supprimée');
    }
}
