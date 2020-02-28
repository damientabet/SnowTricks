<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ForgetService
{
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var object|null
     */
    private $templating;

    /**
     * ForgetService constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param SessionInterface $session
     * @param RouterInterface $router
     * @param UserRepository $userRepository
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $passwordEncoder,
                                SessionInterface $session,
                                RouterInterface $router,
                                UserRepository $userRepository, 
                                ContainerInterface $container)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->router = $router;
        $this->userRepository = $userRepository;
        $this->templating = $container->get('twig');
    }

    /**
     * @param $email
     * @return User|RedirectResponse|null
     */
    public function checkIfUserExist($email)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (is_null($user) || !$user) {
            $this->session->getFlashBag()->add('danger', 'Cette adresse mail ne correspond à aucun utilisateur');
            $response = new RedirectResponse($this->router->generate('forget.passwd'));
            return $response->send();
        }
        return $user;
    }

    /**
     * @param Swift_Mailer $mailer
     * @param $email
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendMail(Swift_Mailer $mailer, $email)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $this->checkIfUserExist($email);
        $message = (new \Swift_Message('Réinitialisation de votre mot de passe'))
            ->setFrom('test.damien.tabet@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($this->templating->render('email/resetPassword.html.twig',[
                'email' => $user->getEmail(),
                'username' => $user->getPseudo(),
                'token' => $user->getSecureKey()
            ]), 'text/html');
        $mailer->send($message);

        $this->session->getFlashBag()->add('success', 'Le mail de réinitialisation à été envoyé à l\'adresse suivante : '.$user->getEmail());
    }

    public function updatePassword(User $user, $data)
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $data));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', 'Le mot de passe à bien été modifié');
        $response = new RedirectResponse($this->router->generate('login'));
        return $response->send();
    }
}
