<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Entity\ReviewJeux;
use App\Form\ReviewJeuxType;
use App\Repository\JeuxRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewJeuxController extends BaseController
{
    #[Route('/review/jeux', name: 'app_review_jeux')]
    public function index(): Response
    {
        return $this->render('review_jeux/index.html.twig', [
            'controller_name' => 'ReviewJeuxController',
        ]);
    }

}
