<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoachingController extends AbstractController
{
    #[Route('/coaching/allCourses', name: 'app_coaching')]
    public function index(): Response
    {
        return $this->render('coaching/allCoaching.html.twig', [
            'controller_name' => 'CoachingController',
        ]);
    }

    #[Route('/coaching/oneCourse', name: 'oneCourse')]
    public function oneCourse(): Response
    {
        return $this->render('coaching/CourseDetails.html.twig', [
            'controller_name' => 'CoachingController',
        ]);
    }


}
