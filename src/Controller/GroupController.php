<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\ReviewJeux;

use App\Form\GroupeType;
use App\Repository\GroupeRepository;
use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    #[Route('/group', name: 'app_group')]
    public function  add(ManagerRegistry $doctrine, Request  $request) : Response
    { $groupe = new Groupe() ;
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->add('ajouter', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $em = $doctrine->getManager();
            $em->persist($groupe);
            $em->flush();
            return $this->redirectToRoute('our_groupe');
        }
        return $this->renderForm("group/index.html.twig",
            ["form"=>$form]) ;


    }


    #[Route('/ourgroupe', name: 'our_groupe')]
    public function affiche(): Response
    {
        $groupe =$this->getDoctrine()->getRepository(Groupe::class)->findAll();
        return $this->render('group/de.html.twig', [
            'groupe' => $groupe
        ]);
    }


/*
    #[Route('/post/{id}', name: 'post')]
    public function post(int $id, GroupeRepository $grouperep, \Doctrine\Persistence\ManagerRegistry $doctrine) : Response
    {
        $post = $doctrine->getRepository(Groupe::class)->find($id);
        return $this->render('group/post.html.twig', [
            'post' => $post,
        ]);
    }
    */


    #[Route('/groupe/{id}', name: 'onegroupe')]
    public function oneCourse(int $id, GroupeRepository $postgroupe)
    {
        $groupe = $postgroupe->find($id);

        return $this->render('group/post.html.twig', [
            'groupe' => $groupe
        ]);
    }

}
