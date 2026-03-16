<?php

namespace App\Form;

use App\Entity\Cabinet;
use App\Entity\Orthophoniste;
use App\Entity\Specialisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class OrthophonisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Exemple : orthophoniste@example.com',
                ],
            ])

            /* ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Orthophoniste' => 'ROLE_ORTHO',
                    'Utilisateur' => 'ROLE_USER',
                ],
                'multiple' => true, // Autorise plusieurs rôles
                'expanded' => true, // Boutons radio ou cases à cocher
            ]) */
            ->add('specialisation', EntityType::class, [
                'class' => Specialisation::class,
                'choice_label' => 'label',
                'multiple' => false,
                'expanded' => false,
            ])


            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez un mot de passe sécurisé',
                ],
            ])

            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'orthophoniste',
                ],
            ])

            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le prénom de l\'orthophoniste',
                ],
            ])

            ->add('imagesOrthophoniste', FileType::class, [
                'multiple' => true,
                'mapped' => false,
            ])

            ->add('telephone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Exemple : 06 12 34 56 78',
                ],
            ])

            /* ->add('createdAt', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text', // Format input HTML5
                'required' => true,
                'attr' => [
                    'placeholder' => 'Sélectionnez une date',
                ],
            ]) */

            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ],
                'placeholder' => 'Sélectionnez votre genre',
                'required' => true,
                'expanded' => false, // Menu déroulant
            ])

            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez la ville d\'exercice',
                ],
            ])
            ->add('cabinet', EntityType::class, [
                'class' => Cabinet::class,
                'choice_label' => 'nom',
            ])
        ;

        // Un champ désactivé pour le nombre d’heures travaillées (commenté ici)
        /* ->add('nombreHeureTravail', IntegerType::class, [
                'label' => 'Nombre d\'heures de travail',
                'required' => false, // Optionnel
                'attr' => [
                    'placeholder' => 'Entrez le nombre d\'heures travaillées par semaine',
                ],
            ]) */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orthophoniste::class, // Associe ce formulaire à l'entité Orthophoniste
        ]);
    }
}
