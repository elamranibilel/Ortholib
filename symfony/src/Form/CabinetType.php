<?php

namespace App\Form;

use App\Entity\Cabinet;
use App\Entity\Orthophoniste;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CabinetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [])
            ->add('imagesCabinet', FileType::class, [
                'multiple' => true,
                'mapped' => false,
            ])
            ->add('adresse', TextType::class, [])
            ->add('telephone', TelType::class, [
                'label' => 'Numéro de téléphone', 
                'constraints' => [
                    new NotBlank(['message' => 'Le téléphone ne peut pas être vide']),
                    new Regex([
                        'pattern' => '/^\+?[0-9\s\(\)\-]+$/',
                        'message' => 'Veuillez entrer un numéro de téléphone valide',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cabinet::class,
        ]);
    }
}
