<?php

namespace App\Controller;

use App\Entity\Environment;
use App\Repository\EnvironmentRepository;
use App\Form\CreateEnvironmentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EnvironmentController extends AbstractController
{
    #[Route('/administration/outils/ajouter', name: 'addEnvironment')]
    public function addEnvironment(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $environment = new Environment();
        $form = $this->createForm(CreateEnvironmentType::class, $environment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $img = $form->get('imageEnvironment')->getData();
            
            if($img !== null)
            {
                
                $mainImage = $form->get('imageEnvironment')->getData();
                
                $originalMainImage = pathinfo($mainImage->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeMainImage = $slugger->slug($originalMainImage);
                $mainImageFilename = $safeMainImage.'-'.uniqid().'.'.$mainImage->guessExtension();
                try {
                    $mainImage->move(
                        $this->getParameter('tools_directory'),
                        $mainImageFilename
                    );
                } 
                    catch (FileException $e) {
                }

                $environment->setImageEnvironment($mainImageFilename);

                $manager->persist($environment);
                $manager->flush();
                $this->addFlash('message', 'Votre projet a été ajouté avec succès');
                return $this->redirectToRoute('addEnvironment');
            }
                else
            {
                $this->addFlash('message', 'Veuillez choisir une image');
                return $this->redirectToRoute('addEnvironment');
            }
        }

        return $this->render('environment/createEnvironment.html.twig', [
            'controller_name' => 'EnvironmentController',
            'formCreateEnvironment' => $form->createView(),
        ]);
    }

    #[Route('/administration/outils/modifier{id}', name: 'modifyEnvironment')]
    public function modifyEnvironment(Environment $environment, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $modify = true;
        $precedentImg = $environment->getImageEnvironment();
        $form = $this->createForm(CreateEnvironmentType::class, $environment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $newImg = $form->get('imageEnvironment')->getData();
            
            if($newImg !== null && $newImg !== $precedentImg)
            {
                
                $mainImage = $form->get('imageEnvironment')->getData();
                
                $originalMainImage = pathinfo($mainImage->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeMainImage = $slugger->slug($originalMainImage);
                $mainImageFilename = $safeMainImage.'-'.uniqid().'.'.$mainImage->guessExtension();
                try {
                    $mainImage->move(
                        $this->getParameter('tools_directory'),
                        $mainImageFilename
                    );
                } catch (FileException $e) {
                }
                $environment->setImageEnvironment($mainImageFilename);
                unlink($this->getParameter('tools_directory').'/'.$precedentImg);
            }
                else
            {
                $environment->setImageEnvironment($precedentImg);
            }

            $manager->persist($environment);
            $manager->flush();
            $this->addFlash('message', 'Les modifications ont bien été prises en compte.');
        }


        return $this->render('environment/createEnvironment.html.twig', [
            'controller_name' => 'EnvironmentController',
            'formCreateEnvironment' => $form->createView(),
            'modify' => $modify,
        ]);
    }

    #[Route('/administration/outils/delete{id}', name: 'deleteEnvironment')]
    public function deleteEnvironment($id, Environment $environment, Request $request, EntityManagerInterface $manager, EnvironmentRepository $environmentRepository): Response
    {
        //delete the image in the folder
        $datasImage = $environmentRepository->findOneById($id);
        $pathImage = $datasImage->getImageEnvironment();
        unlink($this->getParameter('tools_directory').'/'.$pathImage);

        $manager->remove($environment);
        $manager->flush();

        //environments per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number total of environments by autor
        $total = $environmentRepository->getTotalEnvironmentsAdmin();
        //recover environments per page and autor
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
}
