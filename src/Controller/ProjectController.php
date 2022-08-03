<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\CreateProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProjectController extends AbstractController
{
    #[Route('/administration/projets/active/{id}', name:'activeProject')]
    public function activeProject($id, Request $request, EntityManagerInterface $manager, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->findOneById($id);
        $project->setIsActive(1);
        $manager->persist($project);
        $manager->flush();
        
        //projects per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of projects by autor
        $total = $projectRepository->getTotalProjectsAdmin();
        //recover projects per page and autor
        $projects = $projectRepository->getPaginateProjectsAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil((int)$total / $limit);
            
        return $this->redirectToRoute('app_pageAdminProjects', [
            'projects' => $projects,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }

    #[Route('/administration/projets/desactive/{id}', name:'desactiveProject')]
    public function desactiveProject($id, Request $request, EntityManagerInterface $manager, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->findOneById($id);
        $project->setIsActive(0);
        $manager->persist($project);
        $manager->flush();
        
        //projects per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of projects by autor
        $total = $projectRepository->getTotalProjectsAdmin();
        //recover projects per page and autor
        $projects = $projectRepository->getPaginateProjectsAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil((int)$total / $limit);
            
        return $this->redirectToRoute('app_pageAdminProjects', [
            'projects' => $projects,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }

    #[Route('/administration/projets/ajouter', name: 'addProject')]
    public function addProject(EntityManagerInterface $manager, Request $request, SluggerInterface $slugger): Response
    {
        $project = new Project();
        $form = $this->createForm(CreateProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $img = $form->get('imageProject')->getData();
            
            if($img !== null)
            {
                $dateCreateProject = new \DateTime();
                $mainImage = $form->get('imageProject')->getData();

                $project->setDateCreateProject($dateCreateProject)
                    ->setDateUpdateProject($dateCreateProject)
                    ->setIsActive(1);
                
                $originalMainImage = pathinfo($mainImage->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeMainImage = $slugger->slug($originalMainImage);
                $mainImageFilename = $safeMainImage.'-'.uniqid().'.'.$mainImage->guessExtension();
                try {
                    $mainImage->move(
                        $this->getParameter('projects_directory'),
                        $mainImageFilename
                    );
                } 
                    catch (FileException $e) {
                }

                $project->setImageProject($mainImageFilename);

                $manager->persist($project);
                $manager->flush();
                $this->addFlash('message', 'Votre projet a été ajouté avec succès');
                return $this->redirectToRoute('addProject');
            }
                else
            {
                $this->addFlash('message', 'Veuillez choisir une image');
                return $this->redirectToRoute('addProject');
            }
        }
        return $this->render('project/createProject.html.twig', [
            'controller_name' => 'AdministrationController',
            'formCreateProject' => $form->createView(),
        ]);
    }

    #[Route('/administration/projets/modifier{id}', name: 'modifyProject')]
    public function modifyProject(Project $project, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $modify = true;
        $precedentImg = $project->getImageProject();
        $form = $this->createForm(CreateProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $newImg = $form->get('imageProject')->getData();
            
            if($newImg !== null && $newImg !== $precedentImg)
            {
                
                $mainImage = $form->get('imageProject')->getData();
                
                $originalMainImage = pathinfo($mainImage->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeMainImage = $slugger->slug($originalMainImage);
                $mainImageFilename = $safeMainImage.'-'.uniqid().'.'.$mainImage->guessExtension();
                try {
                    $mainImage->move(
                        $this->getParameter('projects_directory'),
                        $mainImageFilename
                    );
                } catch (FileException $e) {
                }
                $project->setImageProject($mainImageFilename);
                unlink($this->getParameter('projects_directory').'/'.$precedentImg);
            }
                else
            {
                $project->setImageProject($precedentImg);
            }

            $dateUpdateProject = new \DateTime();
            $project->setDateUpdateProject($dateUpdateProject);

            $manager->persist($project);
            $manager->flush();
            $this->addFlash('message', 'Les modifications ont bien été prises en compte.');
        }

        return $this->render('project/createProject.html.twig', [
            'controller_name' => 'AdministrationController',
            'formCreateProject' => $form->createView(),
            'modify' => $modify,
        ]);
    }

    #[Route('/administration/projets/delete{id}', name: 'deleteProject')]
    public function deleteProject($id, Project $project, Request $request, EntityManagerInterface $manager, ProjectRepository $projectRepository): Response
    {
        //delete the image in the folder
        $datasImage = $projectRepository->findOneById($id);
        $pathImage = $datasImage->getImageProject();
        unlink($this->getParameter('projects_directory').'/'.$pathImage);

        $manager->remove($project);
        $manager->flush();

        //projects per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of projects by autor
        $total = $projectRepository->getTotalProjectsAdmin();
        //recover projects per page and autor
        $projects = $projectRepository->getPaginateProjectsAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil((int)$total / $limit);

        return $this->render('administration/administrationProjects.html.twig', [
            'controller_name' => 'AdministrationController',
            'projects' => $projects,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }
}
