<?php

namespace App\Form;

use App\Entity\Exercice;
use App\Entity\Signification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SignificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mots', TextType::class, [
                'label' => 'Mot',
                'required' => true,
            ])
            ->add('definition', TextType::class, [
                'label' => 'Définition',
                'required' => true,
            ])
            ->add('exercices', EntityType::class, [
                'class' => Exercice::class,
                'choice_label' => function (Exercice $exercice) {
                    return $exercice->getId() . ' - ' . $exercice->getType();
                },
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false, // Important pour les relations ManyToMany
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Signification::class,
        ]);
    }
}
