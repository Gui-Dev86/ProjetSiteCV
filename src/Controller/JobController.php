<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Form\CreateJobType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class JobController extends AbstractController
{
    #[Route('/administration/jobs/ajouter', name: 'addJob')]
    public function addJob(Request $request, EntityManagerInterface $manager): Response
    {  
        $job = new Job();
        $form = $this->createForm(CreateJobType::class, $job);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($job);
            $manager->flush();
            $this->addFlash('message', 'Votre emploi a été ajouté avec succès');
            return $this->redirectToRoute('addJob');
        }

        return $this->render('job/createJob.html.twig', [
            'controller_name' => 'JobController',
            'formCreateJob' => $form->createView(),
        ]);
    }

    #[Route('/administration/jobs/modifier{id}', name: 'modifyJob')]
    public function modifyJob(Job $job, Request $request, EntityManagerInterface $manager): Response
    {
        $modify = true;
        $form = $this->createForm(CreateJobType::class, $job);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($job);
            $manager->flush();
            $this->addFlash('message', 'Les modifications ont bien été prises en compte.');
        }

        return $this->render('job/createJob.html.twig', [
            'controller_name' => 'JobController',
            'formCreateJob' => $form->createView(),
            'modify' => $modify,
        ]);
    }

    #[Route('/administration/jobs/delete{id}', name: 'deleteJob')]
    public function deleteJob($id, Job $job, Request $request, EntityManagerInterface $manager, JobRepository $jobRepository): Response
    {
        $manager->remove($job);
        $manager->flush();

        //jobs per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number jobs
        $total = $jobRepository->getTotalJobsAdmin();
        //recover jobs
        $jobs = $jobRepository->getPaginateJobsAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil((int)$total / $limit);

        return $this->render('administration/administrationJobs.html.twig', [
            'controller_name' => 'AdministrationController',
            'jobs' => $jobs,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }
}
