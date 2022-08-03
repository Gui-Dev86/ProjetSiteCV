<?php

namespace App\Controller;

use App\Service\MailerService;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
use App\Repository\LangRepository;
use App\Repository\EnvironmentRepository;
use App\Repository\HobbiesRepository;
use App\Repository\JobRepository;
use App\Form\SendMailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, MailerService $mailerService, UserRepository $userRepository, SkillRepository $skillRepository, EnvironmentRepository $environmentRepository, LangRepository $langRepository, HobbiesRepository $hobbiesRepository, ProjectRepository $projectRepository, JobRepository $jobRepository): Response
    {
        $user = $userRepository->findOneByUsername('adminGuiCV');
        $projects = $projectRepository->getProjectsCV();
        $skills = $skillRepository->findAll();
        $langs = $langRepository->findAll();
        $environments = $environmentRepository->findAll();
        $hobbies = $hobbiesRepository->findAll();
        $jobs = $jobRepository->findAll();

        $dateBirthday = $user->getDateBirthday();
        $datetime = new \DateTime();
        $age = $datetime->diff($dateBirthday, true)->y;
        
        $form = $this->createForm(SendMailType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $contactFormData = $form->getData();
            
            $to = 'contact@guillaume-vigneres.fr';
            $from = $contactFormData['email'];
            $subject = 'vous avez reçu un message sur votre CV';
            $html = $contactFormData['email'] . ' vous a envoyé le message suivant: ' . $contactFormData['content'];
            
            $mailerService->sendEmail($to, $from, $subject, $html);

            $this->addFlash('success', 'Vore message a été envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'formSendMail' => $form->createView(),
            'user' => $user,
            'projects' => $projects,
            'skills' => $skills,
            'langs' => $langs,
            'environments' => $environments,
            'hobbies' => $hobbies,
            'jobs' => $jobs,
            'age' => $age,
        ]);
    }
    
    #[route('/mentions-legales', name:'legalsMentions')]
    public function legalsMentions(): Response
    {
        return $this->render('home/legalsMentions.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[route('/politique-de-confidentialite', name:'confidentialPolitic')]
    public function confidentialPolitic(): Response
    {
        return $this->render('home/confidentialPolitic.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
