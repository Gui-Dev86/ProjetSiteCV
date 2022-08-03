<?php

namespace App\Controller;

use App\Service\MailerService;
use App\Repository\UserRepository;
use App\Form\ForgotPasswordType;
use App\Form\NewPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AuthController extends AbstractController
{

    #[Route('/loginAdminCV', name: 'app_loginAdminCV')]
    public function login(AuthenticationUtils $AuthenticationUtils): Response
    {
        $error = $AuthenticationUtils->getLastAuthenticationError();
        $username = $AuthenticationUtils->getLastUsername();
        
        return $this->render('security/login.html.twig', [
            'error' => $error,
            '$username' => $username,
        ]);
    }

    #[Route('/logoutAdminCV', name: 'app_logoutAdminCV')]
    public function logout(): Void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgotPassword', name: 'app_forgotPassword')]
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $manager, MailerService $mailerService): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $userRepository->findOneByUsername($form->getData('username'));

            if ($user !== null)
            {
                $dateUpdate = new \DateTime();
                $confirmationToken = md5(random_bytes(60));
                $user->setDateUpdate($dateUpdate)
                    ->setTokenPass($confirmationToken);
                $manager->persist($user);
                $manager->flush();
             
                $to = $user->getEmail();
                $from = 'guillaume.vigneres@gmail.com';
                $subject = 'Réinitialisation de votre mot de passe';
                $html = $this->renderView('email/newPass.html.twig', [
                    'username' => $user->getUsername(),
                    'id' => $user->getId(),
                    'token' => $user->getTokenPass(),
                    'address' => $request->server->get('SERVER_NAME')
                ]);
            
                $mailerService->sendEmail($to, $from, $subject, $html);
                
                $this->addFlash('success', 'Un email de réinitilisation de votre mot de passe a été envoyé sur l\'email affiliée à votre compte.');
                
                return $this->redirectToRoute('app_forgotPassword');
            }
            else
            {
                $this->addFlash('error', 'Ce nom d\'utilisateur n\'existe pas.');
                return $this->redirectToRoute('app_forgotPassword');
            }
        }

        return $this->render('auth/forgotPassword.html.twig', [
            'controller_name' => 'AuthController',
            'forgotPasswordType' => $form->createView(),
        ]);
    }
    
    #[Route('/newPassword/{id}/{token}', name: 'app_newPassword')]
    public function newPassword(Request $request, $id, $token, UserRepository $userRepository, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager): Response
    {

        $user = $userRepository->findOneById($id);
        $usernameUrl = $user->getUsername();

        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $formDatas = $form->getData();
            $usernameForm = $formDatas->getUsername();
            
            if ($usernameUrl === $usernameForm)
            {
                if($user->getTokenPass() === $token)
                {
                    $password = $hasher->hashPassword($user, $user->getPassword());
                    $user->setPassword($password);
                    $manager->persist($user);
                    $manager->flush();

                    $this->addFlash(
                        'success',
                        "Votre mot de passe a été modifié avec succès."
                    );
                    return $this->redirectToRoute('app_loginAdminCV');
                }
                else
                {
                    $this->addFlash(
                        'error',
                        "La mofification du mot de passe a échoué."
                    );   
                }
            }
            else
            {
                $this->addFlash(
                    'error',
                    "Le nom d'utilisateur saisi ne correspond pas à celui associé à votre compte."
                );  
            }
        }
        return $this->render('auth/newPassword.html.twig', [
            'controller_name' => 'AuthController',
            'newPasswordType' => $form->createView(),
        ]);
    }
}
