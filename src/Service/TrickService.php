<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class TrickService
 * @package App\Service
 */
class TrickService
{
    private $entityManager;
    /**
     * @var User|null
     */
    private $user;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * TrickService constructor.
     * @param AuthenticationUtils $authenticationUtils
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param SessionInterface $session
     * @param RouterInterface $router
     */
    public function __construct(AuthenticationUtils $authenticationUtils,
                                UserRepository $userRepository,
                                EntityManagerInterface $entityManager,
                                SessionInterface $session,
                                RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->user = $userRepository->findOneBy(['email' => $authenticationUtils->getLastUsername()]);
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function addTrick(Trick $trick)
    {
        $trick->setIdUser($this->user);

        $this->entityManager->persist($trick);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'Figure bien ajouté');

        $response = new RedirectResponse($this->router->generate('trick.edit', ['id' => $trick->getId()]));
        return $response->send();
    }

    public function editTrick(Trick $trick)
    {
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'Figure N°' . $trick->getId() . ' modifiée');

        $response = new RedirectResponse($this->router->generate('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]));
        return $response->send();
    }

    /**
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function deleteTrick(Trick $trick)
    {
        foreach ($trick->getVideos() as $video) {
            $this->entityManager->remove($video);
        }

        foreach ($trick->getImages() as $image) {
            $filesystem = new Filesystem();
            $imagePath = 'images/tricks/' . $image->getName();

            if ($filesystem->exists($imagePath)) {
                $filesystem->remove($imagePath);
            }
            $this->entityManager->remove($image);
        }

        foreach ($trick->getComments() as $comment) {
            $this->entityManager->remove($comment);
        }

        $this->entityManager->remove($trick);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'La figure à été supprimé');

        $response = new RedirectResponse($this->router->generate('home'));
        return $response->send();
    }

    public function checkSlug(Trick $trick, $slug)
    {
        if ($trick->getSlug() !== $slug) {
            $response = new RedirectResponse($this->router->generate('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug(),]));
            return $response->send();
        }
    }
}