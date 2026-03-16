<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\Orthophoniste;
use App\Entity\Specialisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PatientType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => ['placeholder' => 'Entrez votre email'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire']),
                    new Assert\Email(['message' => 'Veuillez entrer un email valide']),
                ],
            ])
            /* ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Patient' => 'ROLE_PATIENT',
                    'Utilisateur' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => true,
            ]) */
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Entrez un mot de passe'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins 6 caractères',
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez votre nom'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez votre prénom'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom est obligatoire']),
                ],
            ])
            ->add('imagesPatient', FileType::class, [
                'multiple' => true,
                'mapped' => false,
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Entrez votre numéro de téléphone'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le téléphone est obligatoire']),
                    new Assert\Regex([
                        'pattern' => '/^\+?\d{10,15}$/',
                        'message' => 'Veuillez entrer un numéro de téléphone valide',
                    ]),
                ],
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Date de Naissance',
                'widget' => 'single_text',
                'attr' => [
                    'max' => (new \DateTime('-4 years'))->format('Y-m-d'),
                ],
                'constraints' => [
                    new Assert\LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'on ne peut pas s\'inscrire si on a moins de 4 ans',
                    ]),
                ]
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ],
                'placeholder' => 'Sélectionnez votre genre',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le genre est obligatoire']),
                ],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Entrez votre ville'],
            ])
            /* ->add('difficulteTest', ChoiceType::class, [
                'label' => 'Difficulté du test',
                'choices' => [
                    'Facile' => 'facile',
                    'Moyen' => 'moyen',
                    'Difficile' => 'difficile',
                ],
                'placeholder' => 'Sélectionnez la difficulté',
            ]) */
            /* ->add('difficulteRencontrees', ChoiceType::class, [
                'label' => 'Difficultés rencontrées',
                'choices' => [
                    'Aucune' => 'aucune',
                    'Problèmes de prononciation' => 'prononciation',
                    'Difficultés de compréhension' => 'comprehension',
                    'Autres' => 'autres',
                ],
                'expanded' => true,
                'multiple' => true,
            ]) */
            ->add('niveauApprentissage', ChoiceType::class, [
                'label' => 'Niveau d\'apprentissage',
                'choices' => [
                    'Débutant' => 'debutant',
                    'Intermédiaire' => 'intermediaire',
                    'Avancé' => 'avance',
                ],
                'placeholder' => 'Sélectionnez votre niveau',
            ])
            /* ->add('deficient', ChoiceType::class, [
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
                ],
                'placeholder' => 'Sélectionnez une option',
            ]) */
            ->add(
                'specialisation',
                EntityType::class,
                [
                    'label' => 'Type de trouble pris en charge',
                    'class' => Specialisation::class,
                    'choice_label' => 'label',
                    'multiple' => false,
                    'expanded' => false,
                    'placeholder' => 'Sélectionnez une option',
                ],
            )
        ;
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('choixOrtho', EntityType::class, [
                'label' => 'Choisir un orthophoniste',
                'class' => Orthophoniste::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un orthophoniste',
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
