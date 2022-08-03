<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
use App\Repository\LangRepository;
use App\Repository\EnvironmentRepository;
use App\Repository\HobbiesRepository;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class AdministrationController extends AbstractController
{
    #[Route('/administration', name: 'app_pageAdmin')]
    public function index(): Response
    {
        return $this->render('administration/administration.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }

    #[Route('/administration/profil', name: 'app_pageAdminProfil')]
    public function pageAdminProfil(Request $request): Response
    {
        return $this->render('administration/administrationProfil.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }

    #[Route('/administration/capacites', name: 'app_pageAdminCapacities')]
    public function pageAdminCapacities(SkillRepository $skillRepository, Request $request): Response
    {
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

    #[Route('/administration/outils', name: 'app_pageAdminEnvironment')]
    public function pageAdminEnvironment(EnvironmentRepository $environmentRepository, Request $request): Response
    {
        //languages per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number environments
        $total =$environmentRepository->getTotalEnvironmentsAdmin();
        //recover environments
        $environments = $environmentRepository->getPaginateEnvironmentsAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil((int)$total / $limit);
        return $this->render('administration/administrationEnvironments.html.twig', [
            'controller_name' => 'AdministrationController',
            'environments' => $environments,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }

    #[Route('/administration/langues', name: 'app_pageAdminLanguages')]
    public function pageAdminLang(LangRepository $langRepository, Request $request): Response
    {
        //languages per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number languages
        $total = $langRepository->getTotalLangsAdmin();
        //recover languages
        $langs = $langRepository->getPaginateLangsAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil((int)$total / $limit);
        return $this->render('administration/administrationLanguages.html.twig', [
            'controller_name' => 'AdministrationController',
            'langs' => $langs,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }

    #[Route('/administration/hobbies', name: 'app_pageAdminHobbies')]
    public function pageAdminHobbies(HobbiesRepository $hobbiesRepository, Request $request): Response
    {
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

    #[Route('/administration/projets', name: 'app_pageAdminProjects')]
    public function pageAdminProjects(ProjectRepository $projectsRepository, Request $request): Response
    {
        //projects per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of comments
        $total = $projectsRepository->getTotalProjectsAdmin();
        //recover comments per page
        $projects = $projectsRepository->getPaginateProjectsAdmin($page, $limit);
        //calculate the pages number
        $pagesNumber = ceil((int)$total / $limit);

        return $this->render('administration/administrationProjects.html.twig', [
            'controller_name' => 'AdministrationController',
            'projects' => $projects,
            'page' => $page,
            'pagesNumber' => $pagesNumber,
        ]);
    }

    #[Route('/administration/jobs', name: 'app_pageAdminJobs')]
    public function pageAdminJobs(JobRepository $jobRepository, Request $request): Response
    {
        //jobs per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of jobs
        $total = $jobRepository->getTotalJobsAdmin();
        //recover jobs per page
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
