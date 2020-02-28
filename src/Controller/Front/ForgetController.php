<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\EmailResetPasswordType;
use App\Form\ResetPasswordType;
use App\Service\ForgetService;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ForgetController extends AbstractController
{
    /**
     * @var ForgetService
     */
    private $forgetService;

    public function __construct(ForgetService $forget)
    {
        $this->forgetService = $forget;
    }

    /**
     * @Route("/forget-password", name="forget.passwd")
     * @param Swift_Mailer $mailer
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function recover(Swift_Mailer $mailer, Request $request)
    {
        $form = $this->createForm(EmailResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $this->forgetService->sendMail($mailer, $email);
        }
        return $this->render('front/user/forget-password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("reset-password/{email}/{username}/{token}", name="reset.passwd",
     *     requirements={
     *     "email"="^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$"
     * })
     * @param string $token
     * @param User $user
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function resetPassword(string $token, User $user, Request $request)
    {
        if ($token !== $user->getSecureKey()) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->forgetService->updatePassword($user, $form->get('password')->getData());
        }

        return $this->render('front/user/reset-password.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
