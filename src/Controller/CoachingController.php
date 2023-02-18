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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CoachingController extends BaseController
{
    //ADMIN
    #[Route('/Admin/allCourses', name: 'Admincoaching')]
    public function adminallCourses(ManagerRegistry $doctrine): Response
    {
        $courses= $doctrine->getRepository(Cours::class)->findAll();
        return $this->render('coaching/adminSeeCourses.html.twig',['courses'=>$courses]);
    }

    #[Route('/Course/true/{id}', name: 'updateStateTrue')]
    public function acceptCourse(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request, int $id): Response
    {
        $course = $doctrine->getRepository(Cours::class)->find($id);
        if($course)
        {
            $course->setEtat(1);
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('Admincoaching',['courseFound'=>true]);
        }else
            return $this->redirectToRoute('Admincoaching',['courseFound'=>false]);
    }

    #[Route('/Course/false/{id}', name: 'updateStateFalse')]
    public function refusertCourse(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request, int $id): Response
    {
        $course = $doctrine->getRepository(Cours::class)->find($id);
        if($course)
        {
            dump($course->getEtat()); // output the current value of the etat field
            $course->setEtat(-1);
            dump($course->getEtat()); // output the new value of the etat field

            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('Admincoaching',['courseFound'=>true]);
        }else
            return $this->redirectToRoute('Admincoaching',['courseFound'=>false]);
    }
    //others

    #[Route('/coaching/addC', name: 'addC')]
    public function addC(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger): Response
    {
        $course =new Cours();
        $course->setEtat(0);
        //get coach with id logged in now
        $coach= $this->managerRegistry->getRepository(Coach::class)->findOneBy((['id' => $request->getSession()->get('Coach_id')]));
        $course->setIdCoach($coach);
        $coachId = $course->getIdCoach()->getId();
        $form =$this->createForm(AddCourseType::class,$course);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {

// get the uploaded file
            $photoC = $form->get('picture')->getData();
            $videoC =  $form->get('videoC')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photoC && $videoC) {
                $originalImgName = pathinfo($photoC->getClientOriginalName(), PATHINFO_FILENAME);
                $originalVidName = pathinfo($videoC->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeImgname = $slugger->slug($originalImgName);
                $safeVidname = $slugger->slug($originalVidName);
                $newImgename = $safeImgname.'-'.uniqid().'.'.$photoC->guessExtension();
                $newVidename = $safeVidname.'-'.uniqid().'.'.$videoC->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoC->move(
                        $this->getParameter('img_directory'),
                        $newImgename
                    );
                    $videoC->move(
                        $this->getParameter('vid_directory'),
                        $newVidename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $course->setImage($newImgename);
                $course->setVideo($newVidename);
            }


            $em =$doctrine->getManager();
            $em->persist($course);
            $em->flush();
            return $this->redirectToRoute('CoachCourses', ['id' => $coachId, 'etat' => 0]);

        }
        return $this->renderForm('coaching/addCourse.html.twig',
            [
                "form"=>$form
            ]);
    }

    #[Route('/coaching/allCourses', name: 'app_coaching')]
    public function allCourses(ManagerRegistry $doctrine): Response
    {
        $courses= $doctrine->getRepository(Cours::class)->findAll();
        return $this->render('coaching/allCoaching.html.twig',['courses'=>$courses]);
    }

    #[Route('/Course/Delete/{id}', name: 'suppC')]
    public function supp(ManagerRegistry $doctrine , int $id): Response
    {
        $course= $doctrine->getRepository(Cours::class)->find($id);
        $coachId= $course->getIdCoach()->getId();
        $em = $doctrine->getManager();
        $em->remove($course);
        $em->flush();

        return $this->redirectToRoute('CoachCourses', ['id' => $coachId, 'etat' => 1]);
    }

    #[Route('/Course/update/{id}', name: 'updateC')]
    public function updateC(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request, int $id): Response
    {

        $course = $doctrine->getRepository(Cours::class)->find($id);
        $coachId = $course->getIdCoach()->getId();
        $form =$this->createForm(AddCourseType::class,$course);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid())
        {
            $course->setEtat(0);
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('CoachCourses', ['id' => $coachId, 'etat' => 0]);
        }
        return $this->renderForm('Coaching/updateCourse.html.twig',[
            'form'=>$form
        ]);
    }

    #[Route('/coach/{id}/courses/{etat}', name: 'CoachCourses')]
    public function showCoachCourses($id,int $etat, CoursRepository $courseRepository)
    {
        $EtatCourses = $courseRepository->findCoursesByCoachIdEtat($id,$etat);
        $Allcourses = $courseRepository->findCoursesByCoachId($id);

        return $this->render('coaching/oneCoachCourses.html.twig', [
            'Allcourses' => $Allcourses,
            'EtatCourses' => $EtatCourses
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

        return $this->redirectToRoute('GamerCourses');
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
        return $this->redirectToRoute('GamerCourses');
    }

    #[Route('/gamer/wishlist', name: 'GamerCourses')]
    public function showWishlist(Request $request)
    {
        $gamer= $this->managerRegistry->getRepository(Gamer::class)->findOneBy((['id' => $request->getSession()->get('Gamer_id')]));
        $GamerWishlist = $gamer->getUserCourses();

        return $this->render('coaching/afficherWishlist.html.twig', [
            'wishlist' => $GamerWishlist
        ]);
    }



}
