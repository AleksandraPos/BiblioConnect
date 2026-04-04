<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

use App\Entity\Produit;
use App\Repository\ProduitRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods:['GET','POST'])]
    public function index(Request $request, ArticleRepository $articleRepository,
                          ProduitRepository $produitRepository): Response
    {
        $articles = $articleRepository->findRecentPublished();
        $produits = $produitRepository->findRecentPublished();

        //reponse simple
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'produits' => $produits
        ]);
    }
}
