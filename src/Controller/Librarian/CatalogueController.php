<?php

namespace App\Controller\Librarian;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CatalogueController extends AbstractController
{
    #[Route('/librarian', name: 'app_librarian_catalogue')]
    public function index(): Response
    {
        return $this->render('librarian/catalogue/index.html.twig', [
            'controller_name' => 'CatalogueController',
            
        ]);
    }
}
