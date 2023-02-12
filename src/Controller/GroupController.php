<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    #[Route('/group', name: 'app_group')]
    public function index(): Response
    {
        return $this->render('group/index.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }


    #[Route('/ourgroupe', name: 'our_groupe')]
    public function affiche(): Response
    {
        return $this->render('group/de.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }
}
