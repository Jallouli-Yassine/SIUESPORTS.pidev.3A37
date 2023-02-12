<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\NewsRepository;
use App\Repository\CommentaireNewsRepository;

class CommentaireNewsController extends AbstractController
{
    #[Route('/commentaire/news', name: 'app_commentaire_news')]
    public function index(): Response
    {
        return $this->render('news/comments.html.twig', [
            'controller_name' => 'CommentaireNewsController',
        ]);
    }
    #[Route('/news/{id}', name: 'news')]
    public function news(Request $request, NewsRepository $newsRepository, CommentaireNewsRepository $commentRepository, $id): Response
    {
        $news = $newsRepository->findOneBy(['id' => $id]);
        $comments = $commentRepository->findBy(['idNews' => $news]);
        $names = [];

        foreach ($comments as $comment) {
            $user = $comment->getUser();
            $names[] = $user->getNom() . ' ' . $user->getPrenom();
        }

        return $this->render('news/comments.html.twig', [
            'news' => $news,
            'comments' => $comments,
            'names' => $names
        ]);
    }
}
