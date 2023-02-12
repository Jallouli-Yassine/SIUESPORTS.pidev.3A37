<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
