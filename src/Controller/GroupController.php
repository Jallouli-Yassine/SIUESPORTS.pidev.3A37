<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Post;
use App\Entity\ReviewJeux;

use App\Form\GroupeType;
use App\Form\PostType;
use App\Repository\GroupeRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends BaseController
{
    #[Route('/group', name: 'app_group')]
    public function  add(ManagerRegistry $doctrine, Request  $request) : Response
    { $groupe = new Groupe() ;
        $fifi = $this->createForm(GroupeType::class, $groupe);
        $fifi->add('ajouter', SubmitType::class) ;
        $fifi->handleRequest($request);
        if ($fifi->isSubmitted())
        { $groupe->setIdOwner($this->session->get('Gamer_id'));
            $groupe->setNbrUser($groupe->getNbrUser()+1);
            $em = $doctrine->getManager();
            $em->persist($groupe);
            $em->flush();
            return $this->redirectToRoute('our_groupe');
        }
        return $this->renderForm("group/addg.html.twig",
            ["form"=>$fifi]) ;


    }




    #[Route('/ourgroupe', name: 'our_groupe')]
    public function affiche(): Response
    {
        $groupe =$this->getDoctrine()->getRepository(Groupe::class)->findAll();
        return $this->render('group/allgroupe.html.twig', [
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
    public function oneCourse(int $id, GroupeRepository $postgroupe, Request $request, EntityManagerInterface $em)
    {
        $groupe = $postgroupe->find($id);
        $post = new Post();
        $post->setIdGroupe($groupe); // définir l'objet Groupe sur l'objet Post
        $form = $this->createForm(PostType::class, $post);
        $form->add('ajouter', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('onegroupe', ['id' => $id]);
        }

        return $this->render('group/post.html.twig', [
            'groupe' => $groupe,
            'form' => $form->createView(),
        ]);
    }


/* remove postee*/
    #[Route("/delete/{id}", name:'delete_post')]
    public function delete($id, ManagerRegistry $doctrine, PostRepository $postRepository)
    {
        $post = $postRepository->find($id);
        $groupId = $post->getIdGroupe()->getId();

        $c = $doctrine
        ->getRepository(Post::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($c);
        $em->flush() ;
        return $this->redirectToRoute('onegroupe', ['id' => $groupId]);
    }


    #[Route('/post/edit/{id}', name: 'edit_post')]
    public function editPost(Post $post, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->add('modifier', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            $em->flush();

            return $this->redirectToRoute('onegroupe', ['id' => $post->getIdGroupe()->getId()]);
        }

        return $this->render('group/updatepost.html.twig', [
            'form' => $form->createView(),
        ]);
    }








}
