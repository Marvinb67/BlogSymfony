<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository, ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $categoryRepository->findAll();
        $articles = $paginator->paginate(
            $articleRepository->findBy([], ['createdAt' => 'DESC'], 10),
            $request->query->getInt('page', 1), 12
        );

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'articles' => $articles
        ]);
    }
}
