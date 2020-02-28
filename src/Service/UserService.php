<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session,
                                RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
        $this->router = $router;
    }

    public function addUser(User $user, $password)
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setCreatedAt(new \DateTime());
        $user->setRoles(['ROLE_USER']);
        $user->setSecureKey(bin2hex(random_bytes(16)));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'Votre compte à été crée');
        $response = new RedirectResponse($this->router->generate('login'));
        return $response->send();
    }
}
