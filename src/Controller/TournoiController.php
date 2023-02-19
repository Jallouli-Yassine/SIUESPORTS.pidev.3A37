<?php

namespace App\Controller;

use App\Entity\Gamer;
use App\Entity\Groupe;
use App\Entity\Membre;
use App\Entity\Team;
use App\Entity\Tournoi;
use App\Form\Team2Type;
use App\Form\Tournoi2Type;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\TeamType;
use App\Form\TournoiType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournoiController extends BaseController
{

    #[Route('/tournoi', name: 'tournoi')]
    public function tournoi(ManagerRegistry $doctrine): Response
    {
        $tournois= $doctrine->getRepository(Tournoi::class)->findAll();
        return $this->render('tournoi/tournoi.html.twig',['tournois'=>$tournois]);

    }
    #[Route('/backtournoi', name: 'backtournoi')]
    public function batournoi(ManagerRegistry $doctrine): Response
    {
        $tournois= $doctrine->getRepository(Tournoi::class)->findAll();
        return $this->render('tournoi/tournoiback.html.twig',['tournoi'=>$tournois]);

    }



    #[Route('/teams', name: 'teams')]
    public function teams(ManagerRegistry $doctrine): Response
    {
        $teams= $doctrine->getRepository(Team::class)->findAll();
        return $this->render('tournoi/teams.html.twig',['teams'=>$teams]);

    }
    #[Route('/mygroup/{id}', name: 'mygroup')]
    public function mygroup(int $id, Request $request , ManagerRegistry $doctrine): Response
    {
        $gamer= $this->managerRegistry->getRepository(Gamer::class)->findOneBy((['id' => $request->getSession()->get('Gamer_id')]));
        $team = $doctrine->getRepository(Team::class)->find($id);
        $count = $doctrine->getRepository(Membre::class)->count(['idTeam' => $id]);
        $groupemember = new Membre();
        $groupemember->setIdGamer($gamer);
        $groupemember->setIdTeam($team);
        $groupemember->setPoint(11); // Set the
        if($count<$team->getNbJoueurs())
        {$em = $doctrine->getManager();
            $em->persist($groupemember);
            $em->flush();}
        $groupemember= $doctrine->getRepository(Membre::class)->findBy(['idTeam' => $id]);
        return $this->render('tournoi/mygroup.html.twig', [
            't' =>$team,'c'=>$count,'m'=>$groupemember
        ]);
    }
    #[Route('/addteam', name: 'addteam')]
    public function addgroup(SluggerInterface $slugger,ManagerRegistry $doctrine, Request  $request): Response
    {

        $team = new Team() ;
        $team->setIdOwner($this->session->get('Gamer_id'));

        $form = $this->createForm(TeamType::class, $team);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $photoC = $form->get('logos')->getData();
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
                $team->setlogo($newImgename);
            }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents

            $em = $doctrine->getManager();
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('teams');
        }
        return $this->renderForm("tournoi/addteam.html.twig",
            ["form"=>$form]) ;
    }
    #[Route('/addtournoi', name: 'addtourno')]
    public function addtournoi(SluggerInterface $slugger,ManagerRegistry $doctrine, Request  $request): Response
    {

        $tournoi = new Tournoi() ;
        $form = $this->createForm(TournoiType::class, $tournoi);

        $form->handleRequest($request);

    if($form->isSubmitted()&& $form->isValid()){
        $tournoi->setEtat(0);
        $photoC = $form->get('logos')->getData();
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
            $tournoi->setImage($newImgename);
        }
            $em = $doctrine->getManager();
            $em->persist($tournoi);
            $em->flush();
            return $this->redirectToRoute('tournoi');
        }
        return $this->renderForm("tournoi/addtournoi.html.twig",
            ["form"=>$form]) ;
    }

    #[Route('/update/{id}', name: 'updateteam')]
    public function  update(SluggerInterface $slugger,ManagerRegistry $doctrine,$id,  Request  $request) : Response
    {
        $team = $doctrine
        ->getRepository(Team::class)
        ->find($id);

        $form = $this->createForm(Team2Type::class, $team);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $photoC = $form->get('logos')->getData();
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
                $team->setlogo($newImgename);
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents

            $em = $doctrine->getManager();
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('teams');
        }
        return $this->renderForm("tournoi/updateteam.html.twig",
            ["form"=>$form,

            ]) ;


    }
    #[Route("/delete/{id}", name:'deleteteam')]
    public function delete($id, ManagerRegistry $doctrine)
    {$t = $doctrine
        ->getRepository(Team::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($t);
        $em->flush() ;
        return $this->redirectToRoute('teams');
    }
    #[Route('/updatetournoi/{id}', name: 'updatetournoi')]
    public function  updatetournoi(SluggerInterface $slugger,ManagerRegistry $doctrine,$id,  Request  $request) : Response
    {
        $tournoi = $doctrine
        ->getRepository(Tournoi::class)
        ->find($id);
        $form = $this->createForm(Tournoi2Type::class, $tournoi);

        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())
        {
            $photoC = $form->get('logos')->getData();
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
                $tournoi->setImage($newImgename);
            }
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('tournoi');
        }
        return $this->renderForm("tournoi/updatetournoi.html.twig",
            ["form"=>$form]) ;


    }
    #[Route("/deletetournoi/{id}", name:'deletetournoi')]
    public function deletetournoi($id, ManagerRegistry $doctrine)
    {
        $t = $doctrine
        ->getRepository(Tournoi::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($t);
        $em->flush() ;
        return $this->redirectToRoute('tournoi');
    }
    #[Route('/tournoi/true/{id}', name: 'updattournoi')]
    public function accepttournoi(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request, int $id): Response
    {
        $tournoi = $doctrine->getRepository(Tournoi::class)->find($id);
        if($tournoi)
        {
            $tournoi->setEtat(1);
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('backtournoi',['tournoi'=>true]);
        }else
            return $this->redirectToRoute('backtournoi',['tournoi'=>false]);
    }

    #[Route('/tournoi/false/{id}', name: 'tournoiFalse')]
    public function refuserttournoi(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request, int $id): Response
    {
        $t = $doctrine->getRepository(Tournoi::class)->find($id);
        if($t)
        {
             $t->setEtat(-1);

            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('backtournoi',['tournoiFound'=>true]);
        }else
            return $this->redirectToRoute('backtournoi',['tournoiFound'=>false]);
    }
    #[Route('/ajoutmember', name: 'ajoutmember')]
    public function ajoutmember(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request): Response
    {

        return $this->renderForm("tournoi/addtournoi.html.twig",
            ["form"=>$form]) ;
    }
}
