<?php

namespace App\Controller;

use App\Entity\News;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    private $managerRegistry;
    public function __construct(private ManagerRegistry $managerRegistry2)
    {
        $this->managerRegistry = $managerRegistry2;
    }

    /*
     * fonction d'affichage pour les actualités
     */
    #[Route('/news', name: 'afficher_les_news')]
    public function afficher_les_news(): Response
    {
        $news = $this->managerRegistry->getRepository(News::class);
        $news= $news->findAll();
        return $this->render('news/index.html.twig',array('news' => $news));
    }
}
