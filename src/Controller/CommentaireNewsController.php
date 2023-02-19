<?php

namespace App\Controller;

use App\Entity\CommentaireNews;
use App\Entity\News;
use App\Form\CommentaireNewsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    private function getUserNames(array $comments): array
    {
        $names = [];
        foreach ($comments as $comment) {
            $user = $comment->getUser();
            $names[] = [
                'id' => $user->getId(),
                'name' => $user->getNom() . ' ' . $user->getPrenom()
            ];
        }
        return $names;
    }


    #[Route('/news/{id}', name: 'news')]
    public function news(Request $request, NewsRepository $newsRepository, CommentaireNewsRepository $commentRepository, $id): Response
    {
        $news = $newsRepository->findOneBy(['id' => $id]);

        $user = $this->getUserFromSession();
        $commentaire = new CommentaireNews();
        $commentaire->setUser($user);
        $commentaire->setIdNews($news);

        $form = $this->createForm(CommentaireNewsType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->managerRegistry->getManagerForClass(CommentaireNews::class);
            $em->persist($commentaire);
            $em->flush();
        }

        $offset = $request->query->get('offset', 0);
        $limit = 100;

        $comments = $commentRepository->findBy(['idNews' => $news], ['date' => 'DESC'], $limit, $offset);
        $totalComments = $commentRepository->count(['idNews' => $news]);

        $game = $news->getIdJeux();
        $gameName = $game->getNomGame();
        $names = $this->getUserNames($comments);

        $loadMoreUrl = $this->generateUrl('news', ['id' => $id, 'offset' => $offset + $limit]);

        $commentCount = $commentRepository->count(['user' => $user]);
        $canAddComment = $commentCount < 3;

        return $this->render('news/comments.html.twig', [
            'news' => $news,
            'comments' => $comments,
            'names' => $names,
            'gameName' => $gameName,
            'form' => $form->createView(),
            'loadMoreUrl' => $loadMoreUrl,
            'hasMoreComments' => ($offset + $limit < $totalComments),
            'canAddComment' => $canAddComment,
        ]);
    }




    #[Route('/newsback/{id}', name: 'comment_back')]
    public function news_back(Request $request, NewsRepository $newsRepository, CommentaireNewsRepository $commentRepository, $id): Response
    {
        $news = $newsRepository->findOneBy(['id' => $id]);
        $user = $this->getUserFromSession();
        $commentaire = new CommentaireNews();
        $commentaire->setUser($user);
        $commentaire->setIdNews($news);

        $form = $this->createForm(CommentaireNewsType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->managerRegistry->getManagerForClass(CommentaireNews::class);
            $em->persist($commentaire);
            $em->flush();
        }
        $comments = $commentRepository->findBy(['idNews' => $news]);
        $game = $news->getIdJeux();
        $gameName = $game->getNomGame();
        $names = $this->getUserNames($comments);

        return $this->render('news/commentsback.html.twig', [
            'news' => $news,
            'comments' => $comments,
            'names' => $names,
            'gameName' => $gameName,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimercommentaire/{id}', name: 'supprimer_commentaire')]
    public function delete_comment($id): Response
    {
        $entityManager = $this->managerRegistry->getManagerForClass(CommentaireNews::class);
        $comment = $entityManager->getRepository(CommentaireNews::class)->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('No comment found for id '.$id);
        }

        $entityManager->remove($comment);
        $entityManager->flush();
        return $this->redirectToRoute('comment_back', ['id' => $comment->getIdNews()->getId()]);
    }
}
