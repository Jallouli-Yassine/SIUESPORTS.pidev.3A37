<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewJeuxController extends AbstractController
{
    #[Route('/review/jeux', name: 'app_review_jeux')]
    public function index(): Response
    {
        return $this->render('review_jeux/index.html.twig', [
            'controller_name' => 'ReviewJeuxController',
        ]);
    }
}
