<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends BaseController
{


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

    #[Route('/news_back', name: 'afficher_les_news_back')]
    public function newsBack(Request $request): Response
    {
        $newsRepository = $this->managerRegistry->getRepository(News::class);
        $news = $newsRepository->findAll();

        $newNews = new News();
        $form = $this->createForm(NewsType::class, $newNews);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->managerRegistry->getManagerForClass(News::class);
                $entityManager->persist($newNews);
                $entityManager->flush();

                // Redirect to the same page to avoid resubmitting the form on refresh
                return $this->redirectToRoute('afficher_les_news_back');
            }
        }

        return $this->render('news/newsback.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete_news/{id}', name: 'supprimer_news')]
    public function delete_news($id): Response
    {
        $entityManager = $this->managerRegistry->getManagerForClass(News::class);
        $news = $entityManager->getRepository(News::class)->find($id);

        if (!$news) {
            throw $this->createNotFoundException('No news found for id '.$id);
        }

        $entityManager->remove($news);
        $entityManager->flush();
        return $this->redirectToRoute('afficher_les_news_back');
    }


    #[Route('/modifiernews/{id}', name: 'modifier_news')]
    public function modifier_news(Request $request,NewsRepository $repo, int $id): Response
    {
        $news = new News();
        $news=$repo->findOneBy(['id' => $id]);
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->managerRegistry->getManagerForClass(Jeux::class);
            $em->persist($news);
            $em->flush();
            return $this->redirect($this->generateUrl('afficher_les_news_back'));
        }
        return $this->renderForm('news/newsback.html.twig', [
            'form' => $form,
            'news'=> $news
        ]);
    }



}
