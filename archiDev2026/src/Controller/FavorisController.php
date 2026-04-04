<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Produit;
use App\Entity\Favoris;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[isGranted('ROLE_USER')]
#[Route('/favoris')]
final class FavorisController extends AbstractController
{
   
    #[Route('/{id}', name: 'app_favoris_ajout')]
    public function index(Produit $produit, EntityManagerInterface $entityManager): Response
    {
       $favoris =  new Favoris;
       $favoris->setUserFavoris($this->getUser());
       $favoris->setProduit($produit);

       $entityManager->persist($favoris);
       $entityManager->flush();

       return $this->redirectToRoute('app_home');
    }

    #[Route('/{id}/delete', name: 'app_favoris_delete')]
    public function delete(Favoris $favoris, EntityManagerInterface $entityManager): Response
    {
       $entityManager->remove($favoris);
       $entityManager->flush();

       return $this->redirectToRoute('app_profile');
    }
    
}
