<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\Exercice;
use App\Entity\Orthophoniste;
use App\Entity\Signification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ExerciceType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Vocabulaire' => 'Vocabulaire',
                    'Mémoire' => 'Mémoire',
                    'Orthographe' => 'Orthographe',
                ],
            ])
            /* ->add('chronometre')
            ->add('score') */
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'nom',
            ]);
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('orthophoniste', EntityType::class, [
                'class' => Orthophoniste::class,
                'choice_label' => 'nom',
            ]);
        } else if ($this->security->isGranted('ROLE_ORTHO')) {
            /* $builder->add('orthophoniste', EntityType::class, [
                'class' => Orthophoniste::class,
                'choice_label' => 'nom',
                'disabled' => true
            ]); */
        };
        $builder->add('signification', EntityType::class, [
            'class' => Signification::class,
            'choice_label' => 'mots',
            'multiple' => true,
            'expanded' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercice::class,
        ]);
    }
}
