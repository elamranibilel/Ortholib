<?php

namespace App\Form;

use App\Entity\Specialisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', ChoiceType::class, [
                'label' => 'Type de trouble pris en charge',
                'choices' => [
                    'Aucun' => 'aucun',
                    'Trouble du langage oral (parole, articulation, bégaiement)' => 'langage_oral',
                    'Trouble du langage écrit (dyslexie, dysorthographie)' => 'langage_ecrit',
                    'Trouble auditif (malentendant, implant cochléaire)' => 'trouble_auditif',
                    'Trouble neuro (aphasie, après AVC, maladie neurodégénérative)' => 'trouble_neuro',
                    'Trouble cognitif et développemental (autisme, retard global)' => 'cognitif_dev',
                    'Trouble de la voix (dysphonie, pathologies vocales)' => 'trouble_voix',
                    'Trouble de la déglutition (adulte, gériatrie, post-opératoire)' => 'trouble_deglutition',
                    'Autre' => 'autre',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Specialisation::class,
        ]);
    }
}
