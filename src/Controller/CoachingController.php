<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoachingController extends AbstractController
{
    #[Route('/coaching/allCourses', name: 'app_coache')]
    public function index(): Response
    {
        return $this->render('coaching/allCoaching.html.twig', [
            'controller_name' => 'CoachingController',
        ]);
    }
}
