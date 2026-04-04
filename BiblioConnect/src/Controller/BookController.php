<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
final class BookController extends AbstractController
{
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET', 'POST'])]
public function show(
    \App\Entity\Book $book, 
    \Symfony\Component\HttpFoundation\Request $request, 
    \Doctrine\ORM\EntityManagerInterface $entityManager
): \Symfony\Component\HttpFoundation\Response {
    
    $review = new \App\Entity\Review();
    $form = $this->createForm(\App\Form\ReviewType::class, $review);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté pour laisser un avis.');
            return $this->redirectToRoute('app_login');
        }

        $review->setBook($book);
        $review->setUserAccount($this->getUser());
        $review->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($review);
        $entityManager->flush();

        $this->addFlash('success', 'Merci pour votre avis !');
        return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
    }

    return $this->render('book/show.html.twig', [
        'book' => $book,
        'reviewForm' => $form->createView(),
    ]);
}

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/borrow', name: 'app_book_borrow', methods: ['POST', 'GET'])]
public function borrow(Book $book, \Doctrine\ORM\EntityManagerInterface $entityManager): \Symfony\Component\HttpFoundation\Response
{
    $user = $this->getUser();
    if (!$user) {
        $this->addFlash('danger', 'Vous devez être connecté pour emprunter un livre.');
        return $this->redirectToRoute('app_login');
    }

    if ($book->getStock() <= 0) {
        $this->addFlash('danger', 'Désolé, ce livre n\'est plus disponible.');
        return $this->redirectToRoute('app_main');
    }

    $reservation = new \App\Entity\Reservation();
    $reservation->setBook($book);
    $reservation->setUserAccount($user);
    $reservation->setStartDate(new \DateTime());
    $reservation->setEndDate((new \DateTime())->modify('+14 days'));
    $reservation->setStatus('Emprunté');

    $book->setStock($book->getStock() - 1);

    $entityManager->persist($reservation);
    $entityManager->flush();

    $this->addFlash('success', 'Livre emprunté avec succès ! Vous avez 14 jours pour le rendre.');

    return $this->redirectToRoute('app_main');
}


}
