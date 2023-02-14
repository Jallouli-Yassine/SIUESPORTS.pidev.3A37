<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\CategorieType;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        $produit =$this->getDoctrine()->getRepository(Produit::class)->findAll();
        //dd($produit);
        return $this->render('product/store.html.twig', [
            'produit' => $produit
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
            return $this->redirectToRoute('ajoutproduit');
        }
        return $this->renderForm('product/addproduct.html.twig', [
            'form'=>$form
        ]);
    }
}