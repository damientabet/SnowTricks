<?php


namespace App\Service;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class CommentService
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

    public function addComment(Comment $comment, Trick $trick)
    {
        $comment->setIdTrick($trick);
        $comment->setIdUser($this->user);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'Commentaire bien ajouté');

        $response = new RedirectResponse($this->router->generate('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]));
        return $response->send();
    }

    public function editComment($comment, Trick $trick)
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'Commentaire modifié');

        $response = new RedirectResponse($this->router->generate('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]));
        return $response->send();
    }

    public function deleteComment($comment, Trick $trick)
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'Votre commentaire à été supprimé');

        $response = new RedirectResponse($this->router->generate('trick.show', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]));
        return $response->send();
    }
}
