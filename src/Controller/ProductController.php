<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\CategorieType;
use App\Form\ProduitType;

use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(ManagerRegistry $doctrine,Request $request): Response
    {
        $produit =$doctrine->getRepository(Produit::class)->findAll();
        $categorie= $doctrine->getRepository(Categorie::class)->findAll();
        $dinarAmount = $request->request->get('dinarAmount');
        $pointAmount = $dinarAmount * 230;
        //dd($produit);
        return $this->render('product/store.html.twig', [
            'produit' => $produit,
            'c'=>$categorie,
            'dinarAmount' => $dinarAmount,
            'pointAmount' => $pointAmount
        ]);
    }

    #[Route('/consultecategg/{id}', name: 'affichecategg')]
    public function consultcateg(ManagerRegistry $doctrine,Request $request,int $id): Response
    {

        $categorie= $doctrine->getRepository(Categorie::class)->find($id);
        $produit=$categorie->getProduits();
        return $this->renderForm('product/ConsultCategorie.html.twig',
            [
                'produit'=>$produit,
                'c'=>$categorie
            ]);
    }
    #[Route('/consulteproduct/{id}', name: 'afficheproduit')]
    public function oneProduct(int $id, ProduitRepository $produitRepository)
    {
        $produit = $produitRepository->find($id);

        return $this->render('product/produit.html.twig', [
            'p'=>$produit
        ]);
    }
    /*---------------panier------------------*/

    /**
     * @Route("/ajouterAuPanier/{id}", name="ajoutpanier")
     */
    public function ajouterAuPanier(Request $request, $id) {
        // Récupérer le produit à ajouter au panier à partir de l'ID
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);

        // Récupérer le panier actuel depuis la session ou créer un nouveau panier si inexistant
        $panier = $request->getSession()->get('panier', []);

        // Ajouter le produit au panier ou augmenter la quantité si le produit est déjà présent
        if (isset($panier[$id])) {
            $panier[$id]['quantite']++;
        } else {
            $panier[$id] = [
                'id' => $produit->getId(),
                'nom' => $produit->getNom(),
                'prix' => $produit->getPrix(),
                'quantite' => 1
            ];
        }

        // Stocker le panier mis à jour dans la session
        $request->getSession()->set('panier', $panier);

        // Rediriger l'utilisateur vers la page du panier
        return $this->render('product/panier.html.twig', [
            'adproduit' => $produit

        ]);
    }
 /* *****************************  supprimer prod panier   **************************** */

    #[Route('/supprimerDuPanier/{id}', name: 'supprimer_du_panier')]
    public function supprimerDuPanier(Request $request, $id) {
        // Récupérer le panier actuel depuis la session ou créer un nouveau panier si inexistant
        $panier = $request->getSession()->get('panier', []);

        // Vérifier si le produit à supprimer existe dans le panier
        if (isset($panier[$id])) {
            // Supprimer le produit du panier
            unset($panier[$id]);
        }

        // Stocker le panier mis à jour dans la session
        $request->getSession()->set('panier', $panier);

        // Rediriger l'utilisateur vers la page de panier mise à jour
        return $this->render('product/panier.html.twig');
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
            return $this->redirectToRoute('ad_categorie');
        }


        return $this->renderForm('product/categorie.html.twig', [
            'form'=>$form
        ]);
    }
    //ajout product ad
    #[Route('/addp/{idCategorie}', name: 'ajoutproduit')]
    public function addp(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger, $idCategorie): Response
    {

        $produit =new Produit();

        $form =$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            /* img*/
            $photoP = $form->get('imagep')->getData();
            if ($photoP) {
                $originalImgName = pathinfo($photoP->getClientOriginalName(), PATHINFO_FILENAME);

                // this is needed to safely include the file name as part of the URL
                $safeImgname = $slugger->slug($originalImgName);

                $newImgename = $safeImgname . '-' . uniqid() . '.' . $photoP->guessExtension();


                // Move the file to the directory where brochures are stored
                try {
                    $photoP->move(
                        $this->getParameter('img_directory'),
                        $newImgename
                    );

                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setImage($newImgename);
            }
            // Récupérer l'entité Catégorie correspondante à l'ID de catégorie fourni

            /*endimg*/
            // Définir la catégorie pour le produit

            $em =$doctrine->getManager();



            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('ad_categorie');
        }
        return $this->renderForm('product/addproduct.html.twig', [
            'form'=>$form,'id'=>$idCategorie
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
    /*nnnnnnnnnnnnnnnnnnnnn**** categorie *******nnnnnnnnnnnnnnnnnnnnnnnnnn*/
    //list categorie
    #[Route('/categoriead', name: 'ad_categorie')]
    public function adlistec(ManagerRegistry $doctrine): Response
    {
        $categorie =$doctrine->getRepository(Categorie::class)->findAll();

        //dd($produit);
        return $this->render('product/adListeCategorie.html.twig', [
            'adcategorie' => $categorie


        ]);
    }
    /*supp categ*/
    #[Route("/deletec/{id}", name:'supprimercategorie')]
    public function deletec($id, ManagerRegistry $doctrine, ProduitRepository $postRepository)
    {
        $em = $doctrine->getManager();

        // Récupérer le groupe correspondant à l'id
        $categorie= $doctrine->getRepository(Categorie:: class)->find($id);

        // Récupérer tous les produit associés
        $produits = $categorie->getProduits();

        // Supprimer chaque produit
        foreach ($produits as $produit) {
            $em->remove($produit);
        }


        // Supprimer la categorie lui-même
        $em->remove($categorie);

        $em->flush();

        return $this->redirectToRoute('ad_categorie');
    }
    /*updateeeeeeeeeeeeeee categ*/
    #[Route('/modifierc/{id}', name: 'modifierCategorie')]
    public function updatec(Request $request, EntityManagerInterface $em, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('ad_categorie', ['id' => $categorie->getId()]);
        }

        return $this->renderForm('product/admodifiercategorie.html.twig', [
            'form' => $form,
            'categorie' => $categorie,
        ]);
    }












}