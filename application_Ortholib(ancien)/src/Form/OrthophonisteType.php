<?php

namespace App\Form;

use App\Entity\Orthophoniste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrthophonisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('genre')
            ->add('ville')
            ->add('nombreHeureTravail')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orthophoniste::class,
        ]);
    }
}
