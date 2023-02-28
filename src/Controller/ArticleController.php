<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/article/show/{slug}', name: 'app_article_show')]
    public function show(EntityManagerInterface $em, Article $article, CommentRepository $commentRepository, Request $request)
    {
        $comment = new Comment;
        $formComment = $this->createForm(CommentFormType::class, $comment);
        $formComment->handleRequest($request);

        if($formComment->isSubmitted() && $formComment->isValid())
        {
            $comment
                ->setAuthor($this->getUser())
                ->setArticle($article)
                ->setIsActive(1)
            ;

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_article_show', [
                'slug' => $article->getSlug()
            ]);
        }

        $comments = $commentRepository->findBy(['isActive' => 1], ['createdAt' => 'DESC']);
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'formComment' => $formComment->createView()
        ]);
    }

}
