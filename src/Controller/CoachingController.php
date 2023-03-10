<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Cours;
use App\Form\AddCourseType;
use App\Repository\CoursRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoachingController extends BaseController
{
    #[Route('/coaching/allCourses', name: 'app_coaching')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $courses= $doctrine->getRepository(Cours::class)->findAll();
        return $this->render('coaching/allCoaching.html.twig',['courses'=>$courses]);
    }

    #[Route('/coaching/oneCourse/{id}', name: 'oneCourse')]
    public function oneCourse(int $id, CoursRepository $coursRepository)
    {
        $course = $coursRepository->find($id);

        return $this->render('coaching/CourseDetails.html.twig', [
            'course' => $course
        ]);
    }


    #[Route('/coaching/addcours', name: 'addC')]
    public function addcours(Request $request): Response
    {
        $em2 = $this->managerRegistry->getRepository(Coach::class);
        $cours=new Cours();
        $em = $this->managerRegistry->getManagerForClass(Cours::class);
            $em->persist($cours);
            $em->flush();
        $form = $this->createForm(AddCourseType::class, $cours);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $coach=$em2->findOneBy(['id'=>$this->session->get('Coach_id')]);
            $em = $this->managerRegistry->getManagerForClass(Cours::class);
            $cours->setIdCoach($coach);
            $em->persist($cours);
            $em->flush();
            return $this->redirectToRoute('app_coaching');  
        }
        return $this->renderForm('coaching/addCourse.html.twig',
        [
            'form'=>$form
        ]);
    }


    

    #[Route('/coach/{id}/courses', name: 'CoachCourses')]
    public function showCoachCourses($id, CoursRepository $courseRepository)
    {
        $courses = $courseRepository->findCoursesByCoachId($id);

        return $this->render('coaching/oneCoachCourses.html.twig', [
            'courses' => $courses
        ]);
    }

}
