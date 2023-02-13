<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Jeux;
class JeuxController extends AbstractController
{

    private $managerRegistry;
    public function __construct(private ManagerRegistry $managerRegistry2)
    {
        $this->managerRegistry = $managerRegistry2;
    }

    /*
     * fonction d'affichage pour les jeux
     */

    #[Route('/jeux', name: 'afficher_les_jeux')]
    public function afficherjeux(): Response
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


}
