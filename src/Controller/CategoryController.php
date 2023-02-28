<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/category/show/{id}', name: 'app_category_show')]
    public function show(Category $category, ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findBy(['category' => $category->getId()], ['createdAt' => 'DESC'], 10);
        return $this->render('/category/show.html.twig', [
            'category' => $category,
            'articles' => $articles
        ]);
    }
}
