<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Team;
use App\Entity\Tournoi;

use App\Form\TeamType;
use App\Form\TournoiType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournoiController extends AbstractController
{

    #[Route('/tournoi', name: 'tournoi')]
    public function tournoi(): Response
    {
        return $this->render('tournoi/tournoi.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }
    #[Route('/teams', name: 'teams')]
    public function teams(): Response
    {
        return $this->render('tournoi/teams.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }
    #[Route('/mygroup', name: 'mygroup')]
    public function mygroup(): Response
    {
        return $this->render('tournoi/mygroup.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }
    #[Route('/addteam', name: 'addteam')]
    public function addgroup(ManagerRegistry $doctrine, Request  $request): Response
    {
        $team = new Team() ;
        $form = $this->createForm(TeamType::class, $team);
        $form->add('ajouter', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $em = $doctrine->getManager();
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('mygroup');
        }
        return $this->renderForm("tournoi/addteam.html.twig",
            ["form"=>$form]) ;
    }
    #[Route('/addtournoi', name: 'addtourno')]
    public function addtournoi(ManagerRegistry $doctrine, Request  $request): Response
    {
        $tournoi = new Tournoi() ;
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->add('ajouter', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $em = $doctrine->getManager();
            $em->persist($tournoi);
            $em->flush();
            return $this->redirectToRoute('mygroup');
        }
        return $this->renderForm("tournoi/addtournoi.html.twig",
            ["form"=>$form]) ;
    }
}
