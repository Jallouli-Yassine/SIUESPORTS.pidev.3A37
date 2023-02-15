<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Cours;
use App\Entity\Gamer;
use App\Entity\UserCourses;
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
    public function allCourses(ManagerRegistry $doctrine): Response
    {
        $courses= $doctrine->getRepository(Cours::class)->findAll();
        return $this->render('coaching/allCoaching.html.twig',['courses'=>$courses]);
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


    #[Route('/coaching/oneCourse/{id}', name: 'oneCourse')]
    public function oneCourse(int $id, ManagerRegistry $doctrine ,Request $request,CoursRepository $coursRepository)
    {
        $gamer= $this->managerRegistry->getRepository(Gamer::class)->findOneBy((['id' => $request->getSession()->get('Gamer_id')]));
        $em =$doctrine->getManager();
        $course = $doctrine->getRepository(Cours::class)->find($id);
        $isFavorite = $em->getRepository(UserCourses::class)->findOneBy(['idGamer' => $gamer, 'idCours' => $course, 'favori' => true]);

        return $this->render('coaching/CourseDetails.html.twig', [
            'course' => $course,
            'isFavorite'=>$isFavorite
        ]);
    }

    #[Route('/course/{id}/toFavori', name: 'favori_course')]
    public function addToFavoriCourse(ManagerRegistry $doctrine,Request $request, int $id): Response
    {
        $gamer= $this->managerRegistry->getRepository(Gamer::class)->findOneBy((['id' => $request->getSession()->get('Gamer_id')]));
        $course = $doctrine->getRepository(Cours::class)->find($id);

        $gamersCourse = new UserCourses();
        $gamersCourse->setIdGamer($gamer);
        $gamersCourse->setIdCours($course);
        $gamersCourse->setFavori(true);
        $gamersCourse->setAcheter(false);

        $favori = $gamersCourse->isFavori();
        $acheter=$gamersCourse->isAcheter();

        $em =$doctrine->getManager();
        $em->persist($gamersCourse);
        $em->flush();

        return $this->redirectToRoute('oneCourse', ['id' => $id]);
    }

    #[Route('/course/{id}/removeFromFavori', name: 'removeFromFavoriCourse')]
    public function removeFromFavoriCourse(ManagerRegistry $doctrine,Request $request, int $id)
    {

        $em =$doctrine->getManager();

        $gamer= $this->managerRegistry->getRepository(Gamer::class)->findOneBy((['id' => $request->getSession()->get('Gamer_id')]));
        $course = $doctrine->getRepository(Cours::class)->find($id);
        $userCourse =$em->getRepository(UserCourses::class)->findOneBy(['idGamer' => $gamer, 'idCours' => $course, 'favori' => true]);

        if (!$course) {
            throw $this->createNotFoundException('Course not found');
        }
        $em->remove($userCourse);
        $em->flush();
        return $this->redirectToRoute('oneCourse', ['id' => $id]);
    }

}
