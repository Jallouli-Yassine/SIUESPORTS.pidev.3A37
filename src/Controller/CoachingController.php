<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Cours;
use App\Form\AddCourseType;
use App\Repository\CoursRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    #[Route('/coaching/addC', name: 'addC')]
    public function addC(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request): Response
    {
        $course =new Cours();
        $coach= $this->managerRegistry->getRepository(Coach::class)->findOneBy((['id' => $request->getSession()->get('Coach_id')]));
        $course->setIdCoach($coach);
        $coachId = $course->getIdCoach()->getId();
        $form =$this->createForm(AddCourseType::class,$course);
        $form->add('ajouter', SubmitType::class) ;
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em =$doctrine->getManager();
            $em->persist($course);
            $em->flush();
            return $this->redirectToRoute('CoachCourses', ['id' => $coachId]);
        }
        return $this->renderForm('coaching/addCourse.html.twig',
            [
                "form"=>$form
            ]);
    }

    #[Route('/Course/Delete/{id}', name: 'suppC')]
    public function supp(ManagerRegistry $doctrine , int $id): Response
    {
        $course= $doctrine->getRepository(Cours::class)->find($id);
        $coachId= $course->getIdCoach()->getId();
        $em = $doctrine->getManager();
        $em->remove($course);
        $em->flush();
        return $this->redirectToRoute('CoachCourses', ['id' => $coachId]);
    }

    #[Route('/Course/update/{id}', name: 'updateC')]
    public function updateC(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request, int $id): Response
    {

        $course = $doctrine->getRepository(Cours::class)->find($id);
        $coachId = $course->getIdCoach()->getId();
        $form =$this->createForm(AddCourseType::class,$course);
        $form->add('update', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('CoachCourses', ['id' => $coachId]);
        }
        return $this->renderForm('Coaching/updateCourse.html.twig',[
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
