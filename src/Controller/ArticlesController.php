<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository(Article::class)->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/articles/nouveau', name: 'app_article_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article créé avec succès');

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/nouveau.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article_detail')]
    public function detail(Article $article): Response
    {
        return $this->render('articles/detail.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/articles/{id}/modifier', name: 'app_article_modifier')]
    public function modifier(Request $request, Article $article, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash('success', 'Article modifié avec succès');

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/nouveau.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/articles/{id}/supprimer', name: 'app_article_supprimer', methods: ['POST'])]
    public function supprimer(Request $request, Article $article, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {

            $em->remove($article);
            $em->flush();

            $this->addFlash('success', 'Article supprimé avec succès');
        }

        return $this->redirectToRoute('app_articles');
    }
}