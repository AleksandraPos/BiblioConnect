<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile')]
final class ProfileController extends AbstractController
{
    #[Route(name: 'app_profile')]
    public function index(): Response
    {

        $articles = $this->getUser()->getArticles();
        $favoris = $this->getUser()->getFavoris();

        return $this->render('profile/index.html.twig', [
            'articles' => $articles,
            'favoris' => $favoris
        ]);
    }
}
