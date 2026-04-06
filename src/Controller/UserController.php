<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Favorite;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('user/profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/book/{id}/favorite', name: 'app_book_favorite')]
public function toggleFavorite(Book $book, EntityManagerInterface $entityManager, Request $request): Response
{
    $user = $this->getUser();
    if (!$user) return $this->redirectToRoute('app_login');

    $favoriteRepo = $entityManager->getRepository(Favorite::class);
    $favorite = $favoriteRepo->findOneBy(['owner' => $user, 'book' => $book]);

    if ($favorite) {
        $entityManager->remove($favorite);
        $this->addFlash('info', 'Retiré des favoris.');
    } else {
        $favorite = new Favorite();
        $favorite->setOwner($user); 
        $favorite->setBook($book); 
        $entityManager->persist($favorite);
        $this->addFlash('success', 'Ajouté aux favoris.');
    }

    $entityManager->flush();
    return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('app_home'));
}


}
