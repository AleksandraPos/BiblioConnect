<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('rating', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
            'label' => 'Votre note (étoiles)',
            'choices'  => [
                '⭐⭐⭐⭐⭐ (5)' => 5,
                '⭐⭐⭐⭐ (4)' => 4,
                '⭐⭐⭐ (3)' => 3,
                '⭐⭐ (2)' => 2,
                '⭐ (1)' => 1,
            ],
            'attr' => ['class' => 'form-select']
        ])

        ->add('comment', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, [
            'label' => 'Votre commentaire',
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Qu\'avez-vous pensé de ce livre ?',
                'rows' => 4
            ]
        ])
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
