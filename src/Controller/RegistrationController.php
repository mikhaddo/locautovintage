<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Recaptcha\RecaptchaValidator;
use Symfony\Component\Form\FormError;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription/", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, RecaptchaValidator $recaptcha): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // dump($user);

        if ($form->isSubmitted()) {

            if(!$recaptcha->verify($request->request->get('g-recaptcha-response', ''), $request->server->get('REMOTE_ADDR'))){
                $form->addError(new FormError('Recaptcha invalide'));
            }

            if($form->isValid()){

                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // do anything else you need here, like send an email

                return $this->redirectToRoute('app_login');

            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

    }
}
