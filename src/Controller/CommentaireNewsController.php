<?php

namespace App\Controller;

use App\Entity\CommentaireNews;
use App\Form\CommentaireNewsType;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\NewsRepository;
use App\Repository\CommentaireNewsRepository;
class CommentaireNewsController extends BaseController
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
        $game = $news->getIdJeux();
        $gameName = $game->getNomGame();
        foreach ($comments as $comment) {
            $user = $comment->getUser();
            $names[] = $user->getNom() . ' ' . $user->getPrenom();
        }

        $user = $this->getUserFromSession();
        $commentaire = new CommentaireNews();

        $commentaire->setUser($user);
        $commentaire->setIdNews($news);
        $commentaire->setDescription('zaerae');

        $form = $this->createForm(CommentaireNewsType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $em = $this->managerRegistry->getManagerForClass(CommentaireNews::class);
            $em->persist($commentaire);
            $em->flush();
        }


        return $this->render('news/comments.html.twig', [
            'news' => $news,
            'comments' => $comments,
            'names' => $names,
            'gameName' => $gameName,
            'form' => $form->createView()
        ]);
    }

}
