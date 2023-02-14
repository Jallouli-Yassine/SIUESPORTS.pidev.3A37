<?php

namespace App\Controller;

use App\Entity\CommentaireNews;
use App\Form\CommentaireNewsType;
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
        $game = $news->getIdJeux();
        $gameName = $game->getNomGame();

        foreach ($comments as $comment) {
            $user = $comment->getUser();
            $names[] = $user->getNom() . ' ' . $user->getPrenom();
        }
        $form = $this->createForm(CommentaireNewsType::class);

        return $this->render('news/comments.html.twig', [
            'news' => $news,
            'comments' => $comments,
            'names' => $names,
            'gameName' => $gameName,
            'form' => $form->createView()
        ]);
    }

    #[Route('/addcomment', name: 'ajouter_un_commentaire')]
    public function ajouter_un_commentaire(Request $request)
    {
        $commentaireNews = new CommentaireNews();
        $form = $this->createForm(CommentaireNewsType::class, $commentaireNews);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->managerRegistry->getManagerForClass(CommentaireNews::class);
            try {
                $em->persist($commentaireNews);
                $em->flush();
                $this->addFlash('success', 'Comment added successfully');
                return $this->redirectToRoute('news');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'An error occurred while adding the comment');
                return $this->redirectToRoute('news');
            }
        }
        return $this->render('news/comments.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
