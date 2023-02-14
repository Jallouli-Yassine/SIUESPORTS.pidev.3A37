<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\CategorieType;
use App\Form\ProduitType;

use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $produit =$doctrine->getRepository(Produit::class)->findAll();
        $categorie= $doctrine->getRepository(Categorie::class)->findAll();
        //dd($produit);
        return $this->render('product/store.html.twig', [
            'produit' => $produit,
            'c'=>$categorie
        ]);
    }

    /*#[Route('/consulteproduct/{id}', name: 'afficheproduit')]
    public function update(Request $request,int $id): Response
    {
        $produit = $doctrine->getRepository(Produit::class)->find($id);

        return $this->renderForm('product/produit.html.twig',
            [
                'p'=>$produit
            ]);
    }*/
    #[Route('/consulteproduct/{id}', name: 'afficheproduit')]
    public function oneProduct(int $id, ProduitRepository $produitRepository)
    {
        $produit = $produitRepository->find($id);

        return $this->render('product/produit.html.twig', [
            'p'=>$produit
        ]);
    }


    #[Route('/consultePanier', name: 'affichePanier')]
    public function Panier( ProduitRepository $produitRepository)
    {


        return $this->render('product/panier.html.twig', [

        ]);
    }
    //list product
    #[Route('/productad', name: 'ad_product')]
    public function adlistep(ManagerRegistry $doctrine): Response
    {
        $produit =$doctrine->getRepository(Produit::class)->findAll();

        //dd($produit);
        return $this->render('product/adListeProduit.html.twig', [
            'adproduit' => $produit

        ]);
    }
    #[Route('/addc', name: 'ajoutCategorie')]
    public function add(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request): Response
    {
        $categorie =new Categorie();
        $form =$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em =$doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('ajoutCategorie');
        }


        return $this->renderForm('product/categorie.html.twig', [
            'form'=>$form
        ]);
    }
    //ajout product ad
    #[Route('/addp', name: 'ajoutproduit')]
    public function addp(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request): Response
    {

        $produit =new Produit();
        $form =$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em =$doctrine->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('ad_product');
        }
        return $this->renderForm('product/addproduct.html.twig', [
            'form'=>$form
        ]);
    }
    #[Route('/deletep/{id}', name: 'supprimerproduit')]
    public function deletep($id, \Doctrine\Persistence\ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Aucun produit trouvé pour l\'id '.$id);
        }

        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('ad_product');
    }
    #[Route('/modifier/{id}', name: 'modifierProduit')]
    public function update(Request $request, EntityManagerInterface $em, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('ad_product', ['id' => $produit->getId()]);
        }

        return $this->renderForm('product/admodifierproduit.html.twig', [
            'form' => $form,
            'produit' => $produit,
        ]);
    }
}

