<?php
namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function index(Request $request, ArticleRepository $repo): Response
    {
        $search   = $request->query->get('search', '');
        $articles = [];

        if (!empty($search)) {
            $articles = $repo->findBySearch($search);
        }

        return $this->render('search/index.html.twig', [
            'articles' => $articles,
            'search'   => $search,
        ]);
    }
}