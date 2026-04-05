<?php

namespace App\Controller;

use App\Entity\Book;
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
    #[IsGranted('ROLE_USER')]
    public function toggleFavorite(Book $book, EntityManagerInterface $entityManager, Request $request): Response
   {
    /** @var User $user */
    $user = $this->getUser();

    if ($user->getFavorites()->contains($book)) {
        $user->removeFavorite($book);
        $this->addFlash('info', 'Le livre a été retiré de vos favoris.');
    } else {
        $user->addFavorite($book);
        $this->addFlash('success', 'Le livre a été ajouté à vos favoris.');
    }

    $entityManager->flush();

    return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('app_home'));
}


}
