<?php

namespace App\Controller\Front;

use App\Form\EmailResetPasswordType;
use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
