<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\EmailResetPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ForgetController extends AbstractController
{
    public $app_token;

    public function __construct($APP_SECRET_TOKEN)
    {
        $this->app_token = $APP_SECRET_TOKEN;
    }

    /**
     * @Route("/forget-password", name="forget.passwd")
     * @param UserRepository $userRepository
     * @param Swift_Mailer $mailer
     * @param Request $request
     * @return Response
     */
    public function recover(UserRepository $userRepository, Swift_Mailer $mailer, Request $request)
    {
        $form = $this->createForm(EmailResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);
            if (!$user) {
                $this->addFlash('danger', 'Cette adresse mail ne correspond à aucun utilisateur');
                $this->redirectToRoute('forget.passwd');
            }
            $message = (new \Swift_Message('Réinitialisation de votre mot de passe'))
                    ->setFrom('test.damien.tabet@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->renderView('email/resetPassword.html.twig', [
                        'email' => $user->getEmail(),
                        'username' => $user->getPseudo(),
                        'token' => $this->app_token
                    ]), 'text/html');
            $mailer->send($message);

            $this->addFlash('success', 'Le mail de réinitialisation à été envoyé à l\'adresse suivante : '.$user->getEmail());
        }
        return $this->render('front/user/forget-password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("reset-password/{email}/{username}/{token}", name="reset.passwd",
     *     requirements={
     *     "email"="^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$",
     *     "token"="%APP_SECRET_TOKEN%"
     * })
     * @param string $email
     * @param string $username
     * @param $token
     * @param User $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function resetPassword(string $email, string $username, string $token, User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            if ($data['password'] !== $data['password_confirm']) {
                $this->addFlash('danger', 'Les champs ne sont pas identiques');
                return $this->redirectToRoute('reset.passwd', [
                    'email' => $email,
                    'username' => $username,
                    'token' => $token
                ]);
            }
            $user->setPassword($passwordEncoder->encodePassword($user,$form->get('password')->getData()));
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le mot de passe à bien été modifié');

            return $this->redirectToRoute('login');
        }

        return $this->render('front/user/reset-password.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}