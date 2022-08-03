<?php

namespace App\Controller;

use App\Entity\Hobbies;
use App\Repository\HobbiesRepository;
use App\Form\CreateHobbyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class HobbyController extends AbstractController
{
    #[Route('/administration/hobbies/ajouter', name: 'addHobby')]
    public function addHobby(EntityManagerInterface $manager, Request $request): Response
    {
        $hobby = new Hobbies();
        $form = $this->createForm(CreateHobbyType::class, $hobby);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($hobby);
            $manager->flush();
            $this->addFlash('message', 'Votre hobby a été ajouté avec succès');
            return $this->redirectToRoute('addHobby');
        }

        return $this->render('hobby/createHobby.html.twig', [
            'controller_name' => 'HobbyController',
            'formCreateHobby' => $form->createView(),
        ]);
    }

    #[Route('/administration/hobbies/modifier{id}', name: 'modifyHobby')]
    public function modifyHobby(Hobbies $hobby, Request $request, EntityManagerInterface $manager): Response
    {
        $modify = true;
        $form = $this->createForm(CreateHobbyType::class, $hobby);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($hobby);
            $manager->flush();
            $this->addFlash('message', 'Les modifications ont bien été prises en compte.');
        }

        return $this->render('hobby/createHobby.html.twig', [
            'controller_name' => 'HobbyController',
            'formCreateHobby' => $form->createView(),
            'modify' => $modify,
        ]);
    }

    #[Route('/administration/hobbies/delete{id}', name: 'deleteHobby')]
    public function deleteHobby($id, Hobbies $hobbies, Request $request, EntityManagerInterface $manager, HobbiesRepository $hobbiesRepository): Response
    {
        $manager->remove($hobbies);
        $manager->flush();

        //hobbies per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number hobbies
        $total = $hobbiesRepository->getTotalHobbiesAdmin();
        //recover hobbies
        $hobbies = $hobbiesRepository->getPaginateHobbiesAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil((int)$total / $limit);

        return $this->render('administration/administrationHobbies.html.twig', [
            'controller_name' => 'AdministrationController',
            'hobbies' => $hobbies,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }
}
