<?php

namespace App\Controller;

use App\Entity\Produit;
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
    #[Route('/addp', name: 'ajoutproduit')]
    public function add()
    {


        return $this->render('product/addproduct.html.twig', [

        ]);
    }
}