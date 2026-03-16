<?php

namespace App\Form;

use App\Entity\Administrateur;
use App\Entity\User;
use App\Entity\Patient;
use App\Entity\Commentaire;
use App\Entity\Orthophoniste;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'texte',
                CKEditorType::class,
                [
                    'config_name' => 'my_full_toolbar',
                ],
            );
        /* ->add('destinataire', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
                'label' => 'Destinataire',
                'placeholder' => 'Sélectionnez un destinataire',
                'required' => true,
                'attr' => ['class' => 'form-select'],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :rolePatient OR u.roles LIKE :roleOrthophoniste')
                        ->setParameter('rolePatient', '%ROLE_PATIENT%')
                        ->setParameter('roleOrthophoniste', '%ROLE_ORTHO%')
                        ->orderBy('u.nom', 'ASC');
                },
            ]); */
        /* if ($options['data']->getAuteur() instanceof Administrateur) {
            $builder->add('auteur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'label' => 'Auteur',
                'placeholder' => 'Sélectionnez un Auteur',
                'required' => true,
                'attr' => ['class' => 'form-select'],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :rolePatient OR u.roles LIKE :roleOrthophoniste')
                        ->setParameter('rolePatient', '%ROLE_PATIENT%')
                        ->setParameter('roleOrthophoniste', '%ROLE_ORTHO%')
                        ->orderBy('u.nom', 'ASC');
                },
            ]);
        } */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
