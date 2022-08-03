<?php

namespace App\Controller;

use App\Entity\Lang;
use App\Repository\LangRepository;
use App\Form\CreateLangType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class LanguageController extends AbstractController
{

    #[Route('/administration/langues/ajouter', name: 'addLang')]
    public function addLang(EntityManagerInterface $manager, Request $request): Response
    {
        $lang = new Lang();
        $form = $this->createForm(CreateLangType::class, $lang);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($lang);
            $manager->flush();
            $this->addFlash('message', 'La langue a été ajouté avec succès');
            return $this->redirectToRoute('addLang');
        }

        return $this->render('lang/createLang.html.twig', [
            'controller_name' => 'LangController',
            'formCreateLang' => $form->createView(),
        ]);
    }

    #[Route('/administration/langues/modifier{id}', name: 'modifyLang')]
    public function modifyLang(Lang $lang, Request $request, EntityManagerInterface $manager): Response
    {
        $modify = true;
        $form = $this->createForm(CreateLangType::class, $lang);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($lang);
            $manager->flush();
            $this->addFlash('message', 'Les modifications ont bien été prises en compte.');
        }

        return $this->render('lang/createLang.html.twig', [
            'controller_name' => 'LangController',
            'formCreateLang' => $form->createView(),
            'modify' => $modify,
        ]);
    }

    #[Route('/administration/langues/delete{id}', name: 'deleteLang')]
    public function deleteLang($id, Lang $lang, Request $request, EntityManagerInterface $manager, LangRepository $langRepository): Response
    {
        $manager->remove($lang);
        $manager->flush();

        //langs per page
        $limit = 5;
        //recover page
        $page = (int)$request->query->get("page", 1);
        //number langs
        $total = $langRepository->getTotalLangsAdmin();
        //recover langs
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
}
