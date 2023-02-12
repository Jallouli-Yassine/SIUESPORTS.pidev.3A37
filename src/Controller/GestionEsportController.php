<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionEsportController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function welcomefront(): Response
    {
        return $this->render('Front_Template/welcome.html.twig',[
            'controller_name' => 'GestionEsportController',
        ]);
    }
}
