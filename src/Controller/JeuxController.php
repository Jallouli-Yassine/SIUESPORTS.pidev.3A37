<?php

namespace App\Controller;

use App\Entity\ReviewJeux;
use App\Form\ReviewJeuxType;
use App\Repository\JeuxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Jeux;
use App\Form\JeuxType;
class JeuxController extends BaseController
{

    /*
     * fonction d'affichage pour les jeux
     */

    #[Route('/jeux', name: 'afficher_les_jeux')]
    public function afficherjeux(Request $request): Response
    {
        $jeux = $this->managerRegistry->getRepository(Jeux::class);
        $jeux = $jeux->findAll();

        $ratingJeux = [];
        foreach ($jeux as $jeu) {
            $reviews = $jeu->getReviewJeuxes();
            if ($reviews->isEmpty()) {
                $ratingJeux[$jeu->getId()] = null;
                continue;
            }

            $totalRating = 0;
            $reviewCount = 0;
            foreach ($reviews as $review) {
                $totalRating += $review->getRating();
                $reviewCount++;
            }

            $ratingJeux[$jeu->getId()] = $totalRating / $reviewCount;

        }
        return $this->render('jeux/index.html.twig', array(
            'jeux' => $jeux,
            'ratingJeux' => $ratingJeux,
        ));
    }


    #[Route('/jeux_back', name: 'afficher_les_jeux_back')]
    public function afficherjeux_back(Request $request): Response
    {
        $jeux = $this->managerRegistry->getRepository(Jeux::class);
        $jeux = $jeux->findAll();

        $ratingJeux = [];
        foreach ($jeux as $jeu) {
            $reviews = $jeu->getReviewJeuxes();
            if ($reviews->isEmpty()) {
                $ratingJeux[$jeu->getId()] = null;
                continue;
            }

            $totalRating = 0;
            $reviewCount = 0;
            foreach ($reviews as $review) {
                $totalRating += $review->getRating();
                $reviewCount++;
            }

            $ratingJeux[$jeu->getId()] = $totalRating / $reviewCount;
        }

        $jeux_form = new Jeux();
        $ajouter_form = $this->createForm(JeuxType::class, $jeux_form);
        $ajouter_form->handleRequest($request);

        if ($ajouter_form->isSubmitted() && $ajouter_form->isValid()) {
            $em = $this->managerRegistry->getManagerForClass(Jeux::class);
            $em->persist($jeux_form);
            $em->flush();
            return new RedirectResponse($this->generateUrl('afficher_les_jeux_back'));
        }

        return $this->render('jeux/jeuxback.html.twig', array(
            'jeux' => $jeux,
            'ratingJeux' => $ratingJeux,
            'form' => $ajouter_form->createView()
        ));
    }

    #[Route('/delete_jeux/{id}', name: 'supprimer_jeux')]
    public function delete_jeux($id): Response
    {
        $entityManager = $this->managerRegistry->getManagerForClass(Jeux::class);
        $jeux = $entityManager->getRepository(Jeux::class)->find($id);

        if (!$jeux) {
            throw $this->createNotFoundException('No jeux found for id '.$id);
        }

        $entityManager->remove($jeux);
        $entityManager->flush();
        return $this->redirectToRoute('afficher_les_jeux_back');
    }


    #[Route('/modifierjeu/{id}', name: 'modifier_jeu')]
    public function modifier_jeu(Request $request,JeuxRepository $repo, int $id): Response
    {
        $jeux = new Jeux();
        $jeux=$repo->findOneBy(['id' => $id]);
        $form = $this->createForm(JeuxType::class, $jeux);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->managerRegistry->getManagerForClass(Jeux::class);
            $em->persist($jeux);
            $em->flush();
            return $this->redirect($this->generateUrl('afficher_les_jeux_back'));
        }
        return $this->renderForm('jeux/jeuxback.html.twig', [
            'form' => $form,
            'jeux'=> $jeux
        ]);
    }


}
