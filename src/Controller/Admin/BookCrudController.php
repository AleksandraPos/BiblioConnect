<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre du livre'),
            TextField::new('author', 'Auteur'),
            TextField::new('language', 'Langue'),
            TextField::new('isbn', 'ISBN'),
            IntegerField::new('stock', 'Quantité en stock'),

            TextEditorField::new('description', 'Description complète'),
            TextField::new('image', 'Lien de la couverture (Image)'),

            AssociationField::new('category', 'Catégorie'),
        ];
    }
}
