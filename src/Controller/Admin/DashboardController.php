<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Category;
use App\Controller\Admin\BookCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(BookCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('BiblioConnect');
    }

    public function configureMenuItems(): iterable
{
    yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

    yield MenuItem::linkToRoute('Livres', 'fas fa-book', 'admin', [
        'crudAction' => 'index',
        'crudControllerFqcn' => BookCrudController::class,
    ]);

    yield MenuItem::linkToRoute('Réservations', 'fas fa-calendar-check', 'admin', [
        'crudAction' => 'index',
        'crudControllerFqcn' => ReservationCrudController::class,
    ]);

    yield MenuItem::linkToUrl('Retour au site', 'fas fa-eye', '/');
}
}
