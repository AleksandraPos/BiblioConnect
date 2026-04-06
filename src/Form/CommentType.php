<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Note (1 à 5)',
                'attr' => [
                    'min' => 1, 
                    'max' => 5, 
                    'class' => 'form-control',
                    'placeholder' => 'Notez de 1 à 5'
                ]
            ])
            ->add('content', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, [
                'label' => 'Votre avis',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Qu\'avez-vous pensé de ce livre ?'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
