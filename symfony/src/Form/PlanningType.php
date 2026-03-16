<?php

namespace App\Form;

use App\Entity\Orthophoniste;
use App\Entity\Patient;
use App\Entity\Seances;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateHeureDebut', null, [
                'widget' => 'single_text',
            ])
            ->add('dateHeureFin', null, [
                'widget' => 'single_text',
            ])
            ->add('mode')
            ->add('duree')
            ->add('orthophoniste', EntityType::class, [
                'class' => Orthophoniste::class,
                'choice_label' => 'id',
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seances::class,
        ]);
    }
}
