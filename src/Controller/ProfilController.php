<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\PasswordUpdate;
use App\Form\UserModifyDatasFormType;
use App\Form\UserModifyPassFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class ProfilController extends AbstractController
{
    #[Route('/profil/modifier', name: 'app_modifyProfil')]
    public function modifyProfil(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $precedentAvatar = $user->getAvatar();
        $form = $this->createForm(UserModifyDatasFormType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $newAvatar = $form->get('avatar')->getData();
            
            if($newAvatar !== null && $newAvatar !== $precedentAvatar)
            {
                $originalAvatar = pathinfo($newAvatar->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeAvatar = $slugger->slug($originalAvatar);
                $avatarFilename = $safeAvatar.'-'.uniqid().'.'.$newAvatar->guessExtension();
                try {
                    $newAvatar->move(
                        $this->getParameter('avatars_directory'),
                        $avatarFilename
                    );
                } catch (FileException $e) {
                }
                $user->setAvatar($avatarFilename);
                unlink($this->getParameter('avatars_directory').'/'.$precedentAvatar);
            }
            else
            {
                $user->setAvatar($precedentAvatar);
            }
            
            $dateUpdate = new \DateTime();
            
            $user->setDateUpdate($dateUpdate);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Les modifications ont bien été prises en compte.');
        }

        return $this->render('profil/modifyProfil.html.twig', [
            'controller_name' => 'ProfilController',
            'formModifyProfil' => $form->createView(),
        ]);
    }

    #[Route('/profil/modifier/password{id}', name: 'app_modifyProfilPassword')]
    public function modifyPassword(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $passwordUpdate = new PasswordUpdate();
        
        $form = $this->createForm(UserModifyPassFormType::class, $passwordUpdate);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $newPassword = $passwordUpdate->getNewPassword();
            $password = $hasher->hashPassword($user, $newPassword);
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
        }

        return $this->render('profil/modifyPassword.html.twig', [
            'controller_name' => 'ProfilController',
            'formModifyPass' => $form->createView(),
        ]);
    }
}
