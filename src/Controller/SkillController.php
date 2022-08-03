<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Repository\SkillRepository;
use App\Form\CreateSkillType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class SkillController extends AbstractController
{
    #[Route('/administration/capacites/ajouter', name: 'addSkill')]
    public function addSkill(EntityManagerInterface $manager, Request $request): Response
    {
        $skill = new Skill();
        $form = $this->createForm(CreateSkillType::class, $skill);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($skill);
            $manager->flush();
            $this->addFlash('message', 'Votre compétence a été ajouté avec succès');
            return $this->redirectToRoute('addSkill');
        }

        return $this->render('skill/createSkill.html.twig', [
            'controller_name' => 'SkillController',
            'formCreateSkill' => $form->createView(),
        ]);
    }

    #[Route('/administration/capacites/modifier{id}', name: 'modifySkill')]
    public function modifySkill(Skill $skill, Request $request, EntityManagerInterface $manager): Response
    {
        $modify = true;
        $form = $this->createForm(CreateSkillType::class, $skill);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($skill);
            $manager->flush();
            $this->addFlash('message', 'Les modifications ont bien été prises en compte.');
        }

        return $this->render('skill/createSkill.html.twig', [
            'controller_name' => 'SkillController',
            'formCreateSkill' => $form->createView(),
            'modify' => $modify,
        ]);
    }

    #[Route('/administration/capacites/delete{id}', name: 'deleteSkill')]
    public function deleteSkill($id, Skill $skill, Request $request, EntityManagerInterface $manager, SkillRepository $skillRepository): Response
    {
        $manager->remove($skill);
        $manager->flush();

        //skills per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number skills
        $total = $skillRepository->getTotalSkillsAdmin();
        //recover skills
        $skills = $skillRepository->getPaginateSkillsAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil((int)$total / $limit);

        return $this->render('administration/administrationCapacities.html.twig', [
            'controller_name' => 'AdministrationController',
            'skills' => $skills,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }
}
