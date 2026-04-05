<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BookRepository $bookRepository, Request $request): Response
    {
        $search = $request->query->get('q');

        if ($search) {
            $books = $bookRepository->findBySearch($search);
        } else {
            $books = $bookRepository->findAll();
        }

        return $this->render('home/index.html.twig', [
            'books' => $books,
            'searchTerm' => $search,
        ]);
    }

    #[Route('/book/{id}', name: 'app_book_show')]
    public function show(Book $book): Response
    {
        return $this->render('home/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/book/{id}/reserve', name: 'app_book_reserve', methods: ['POST'])]
    public function reserve(Book $book, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour réserver un livre.');
            return $this->redirectToRoute('app_login');
        }

        if ($book->getStock() <= 0) {
            $this->addFlash('danger', 'Désolé, ce livre n\'est plus en stock.');
            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        $reservation = new Reservation();
        $reservation->setBook($book);
        $reservation->setUserAccount($user);
        $reservation->setStartDate(new \DateTime());
        $reservation->setEndDate((new \DateTime())->modify('+14 days'));
        $reservation->setStatus('En attente');

        $book->setStock($book->getStock() - 1); 
        $em->persist($reservation);
        $em->flush();

        $this->addFlash('success', 'Votre réservation a été enregistrée avec succès !');

        return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
    }
}
