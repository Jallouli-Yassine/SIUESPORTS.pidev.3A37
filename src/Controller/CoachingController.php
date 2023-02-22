<?php

namespace App\Controller;
use App\Form\UpdateCourseType;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Coach;
use App\Entity\Cours;
use App\Entity\Gamer;
use App\Entity\UserCourses;
use App\Form\AddCourseType;
use App\Repository\CoursRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
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
    public function acceptCourse(\Doctrine\Persistence\ManagerRegistry $doctrine, int $id): Response
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
            $course->setEtat(-1);
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
    public function supp(ManagerRegistry $doctrine , int $id,Filesystem $filesystem): Response
    {
        $course= $doctrine->getRepository(Cours::class)->find($id);
        $coachId= $course->getIdCoach()->getId();


        // get the filename of the image associated with the course
        $imageFileName = $course->getImage();
        $vidFileName = $course->getVideo();

        $em = $doctrine->getManager();
        $em->remove($course);
        $em->flush();

        // delete the image file from the filesystem
        if ($imageFileName && $filesystem->exists($this->getParameter('img_directory').'/'.$imageFileName)) {
            $filesystem->remove($this->getParameter('img_directory').'/'.$imageFileName);
        }

        // delete the image file from the filesystem
        if ($vidFileName && $filesystem->exists($this->getParameter('vid_directory').'/'.$vidFileName)) {
            $filesystem->remove($this->getParameter('vid_directory').'/'.$vidFileName);
        }

        return $this->redirectToRoute('CoachCourses', [
            'id' => $coachId,
            'etat' => 1,
            'enjoy'=>"course deleted succesfuly !"
        ]);
    }

    #[Route('/Course/update/{id}', name: 'updateC')]
    public function updateC(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request, int $id, SluggerInterface $slugger): Response
    {
        $course = $doctrine->getRepository(Cours::class)->find($id);
        $coachId = $course->getIdCoach()->getId();
        $form = $this->createForm(UpdateCourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get the uploaded file
            $photoC = $form->get('picture')->getData();
            $videoC =  $form->get('videoC')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photoC ) {
                $originalImgName = pathinfo($photoC->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeImgname = $slugger->slug($originalImgName);
                $newImgename = $safeImgname.'-'.uniqid().'.'.$photoC->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoC->move(
                        $this->getParameter('img_directory'),
                        $newImgename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $course->setImage($newImgename);
            }
            if($videoC)
            {
                $originalVidName = pathinfo($videoC->getClientOriginalName(), PATHINFO_FILENAME);
                $safeVidname = $slugger->slug($originalVidName);
                $newVidename = $safeVidname.'-'.uniqid().'.'.$videoC->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $videoC->move(
                        $this->getParameter('vid_directory'),
                        $newVidename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $course->setVideo($newVidename);

            }

            $course->setEtat(0);
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('CoachCourses', [
                'id' => $coachId,
                'etat' => 0,
                'enjoy' => "Course updated successfully! You must wait until the admin review and accept it!"
            ]);
        }

        return $this->renderForm('Coaching/updateCourse.html.twig', [
            'form' => $form
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
        $isBuyed = $em->getRepository(UserCourses::class)->findOneBy(['idGamer' => $gamer, 'idCours' => $course, 'acheter' => true]);

        return $this->render('coaching/CourseDetails.html.twig', [
            'course' => $course,
            'isFavorite'=>$isFavorite,
            'isBuyed'=>$isBuyed
        ]);
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
        }else
        {
            if($userCourse->isAcheter()){
                $userCourse->setFavori(false);
                $em->flush();
                return $this->redirectToRoute('GamerCourses');
            }else
            {
                $em->remove($userCourse);
                $em->flush();
                return $this->redirectToRoute('GamerCourses');
            }
        }
    }

    #[Route('/gamer/courses', name: 'GamerCourses')]
    public function showWishlist(Request $request)
    {
        $gamer= $this->managerRegistry->getRepository(Gamer::class)->findOneBy((['id' => $request->getSession()->get('Gamer_id')]));
        $GamerWishlist = $gamer->getUserCourses();

        $cours = null;
        if ($coursId = $this->request->query->get('cours')) {
            $cours = $this->managerRegistry->getRepository(Cours::class)->find($coursId);
        } else {
            $cours = null;
        }


        return $this->render('coaching/gamerCourses.html.twig', [
            'wishlist' => $GamerWishlist,
            'cours' => $cours,
        ]);
    }
    #[Route('/course/{id}/toFavori', name: 'favori_course')]
    public function addToFavoriCourse(ManagerRegistry $doctrine,Request $request, int $id): Response
    {
        $gamer= $doctrine->getRepository(Gamer::class)->findOneBy((['id' => $request->getSession()->get('Gamer_id')]));
        $course = $doctrine->getRepository(Cours::class)->find($id);

        $gamersCourse = $doctrine->getRepository(UserCourses::class)->findOneBy([
            'idGamer' => $gamer->getId(),
            'idCours' => $course->getId(),
        ]);
        $em =$doctrine->getManager();
        if ($gamersCourse) {
            if ($gamersCourse->isAcheter()) {
                $gamersCourse->setFavori(true);
                    $em->flush();
                // Le gamer a déjà ajouté le cours à sa liste de favoris
                return $this->redirectToRoute('GamerCourses',[
                    'enjoy'=>"enjoy ur new course :D !",
                    'cours'=>$course
                ]);
            }

            if ($gamersCourse->isFavori()) {
                // Le gamer a déjà acheté le cours
                return $this->redirectToRoute('GamerCourses',[
                    'error' =>"le cours est deja au favori"
                ]);
            }
        }else{
            $gamersCourse = new UserCourses();
            $gamersCourse->setIdGamer($gamer);
            $gamersCourse->setIdCours($course);
            $gamersCourse->setFavori(true);
            $gamersCourse->setAcheter(false);
                $em->persist($gamersCourse);
                $em->flush();
        }

        return $this->redirectToRoute('GamerCourses',[
            'enjoy'=>"course added to wishlist succesfuly :D !"
        ]);
    }
    #[Route('/course/{id}/userBuyC', name: 'buy_course')]
    public function buyCourse(ManagerRegistry $doctrine,Request $request, int $id): Response
    {
        $gamer= $this->managerRegistry->getRepository(Gamer::class)->findOneBy((['id' => $request->getSession()->get('Gamer_id')]));
        $course = $doctrine->getRepository(Cours::class)->find($id);

        $gamersCourse = $doctrine->getRepository(UserCourses::class)->findOneBy([
            'idGamer' => $gamer->getId(),
            'idCours' => $course->getId(),
        ]);

        $prix = $course->getPrix();
        $gamerP= $gamer->getPoint();
        $em =$doctrine->getManager();
        if ($gamersCourse){
            if ($gamersCourse->isFavori()) {

                if($prix>$gamerP) return $this->redirectToRoute('GamerCourses',[
                    'error' =>"votre solde des points est insufisant"
                ]);
                else
                {
                    $gamer->setPoint($gamerP-$prix);
                    $this->session->set('Gamer_point', $gamer->getPoint());
                    $gamersCourse->setAcheter(true);
                    //updateCoach points
                    $newPoints = $course->getIdCoach()->getPoint()+$course->getPrix();
                    $course->getIdCoach()->setPoint($newPoints);
                    $em->flush();
                }

                // Le gamer a déjà ajouté le cours à sa liste de favoris
                return $this->redirectToRoute('GamerCourses',[
                    'enjoy'=>"enjoy ur new course :D !",
                    'cours'=>$course
                ]);
            }

            if ($gamersCourse->isAcheter()) {
                // Le gamer a déjà acheté le cours
                return $this->redirectToRoute('GamerCourses',[
                    'error' =>"le cours est deja acheter"
                ]);
            }
        }else{
            if($prix>$gamerP){
                return $this->redirectToRoute('GamerCourses',[
                    'error' =>"votre solde des points est insufisant"
                ]);
            }else{
                $gamersCourse = new UserCourses();
                $gamersCourse->setIdGamer($gamer);
                $gamersCourse->setIdCours($course);
                $gamer->setPoint($gamerP-$prix);

                //updateCoach points
                $newPoints = $course->getIdCoach()->getPoint()+$course->getPrix();
                $course->getIdCoach()->setPoint($newPoints);

                $this->session->set('Gamer_point', $gamer->getPoint());
                $gamersCourse->setFavori(false);
                $gamersCourse->setAcheter(true);

                $em->persist($gamersCourse);
                $em->flush();


            }
        }
        return $this->redirectToRoute('GamerCourses',[
            'enjoy'=>"enjoy ur new course :D !",
            'cours' => $course->getId()
        ]);
    }

    /**
     * @Route("/generate-invoice/{id}", name="generate_invoice")
     */
    public function generateInvoice(Request $request,int $id,ManagerRegistry $doctrine)
    {
        $gamer= $this->managerRegistry->getRepository(Gamer::class)->findOneBy((['id' => $request->getSession()->get('Gamer_id')]));
        $course = $doctrine->getRepository(Cours::class)->find($id);

        $html = $this->renderView('./coaching/pdfFacture.html.twig', [
            'course' => $course,
            'gamer' => $gamer,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();



        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s_%s.pdf"', $gamer->getNom() , $course->getTitre() ));

        return $response;
    }
}
