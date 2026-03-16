<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Entity\Image;
use App\Entity\Patient;
use App\Entity\Orthophoniste;
use App\Form\OrthophonisteType;
use App\Form\SearchType;
use App\Repository\AdministrateurRepository;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrthophonisteRepository;
use App\Repository\PatientRepository;
use App\Repository\ResultatExerciceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/* #[Route('/admin')]
 */

class OrthophonisteController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/orthophoniste', name: 'app_orthophoniste')]
    public function index(Request $request, PaginatorInterface $paginator, OrthophonisteRepository $orthophonisteRepository): Response
    {
        $query = $orthophonisteRepository->findAll();
        $orthophonistes = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10,
        );

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $orthophonistes = $orthophonisteRepository->searchOrthophonistes($query);
            $orthophonistes = $paginator->paginate(
                $orthophonistes,
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('orthophoniste/index.html.twig', [
            'controller_name' => 'OrthophonisteController',
            'orthophonistes' => $orthophonistes,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/choisir-ortho', name: 'choisir_orthophoniste')]
    public function choisirOrtho(Request $request, PaginatorInterface $paginator, OrthophonisteRepository $orthophonisteRepository): Response
    {
        $patient = $this->getUser();
        if ($patient instanceof Patient) {
            if ($patient->getChoixOrtho() && $patient->isConfirmed()) {
                return $this->redirectToRoute('app_espace_patient');
            }

            $query = $orthophonisteRepository->findAll();
            $orthophonistes = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('orthophoniste/index.html.twig', [
                'orthophonistes' => $orthophonistes,
                'form' => $this->createForm(SearchType::class)->createView(),
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
    //affichage Ortho pour sélectionner!!

    #[Route('/orthophoniste/new', name: 'app_orthophoniste_new')]
    public function newOrthophoniste(Request $request, EntityManagerInterface $manager): Response
    {
        $orthophoniste = new Orthophoniste();
        $form = $this->createForm(OrthophonisteType::class, $orthophoniste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orthophoniste->setRoles(['ROLE_ADMIN']);
            $orthophoniste->setPassword($this->passwordHasher->hashPassword($orthophoniste, $orthophoniste->getPassword()));
            $images = $form->get('imagesOrthophoniste')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);

                $img = new Image();
                $img->setName($fichier);
                $orthophoniste->addImagesOrthophoniste($img);
            }
            $orthophoniste->setPassword($this->passwordHasher->hashPassword($orthophoniste, $orthophoniste->getPassword()));
            $manager->persist($orthophoniste);
            $manager->flush();
            return $this->redirectToRoute('app_orthophoniste');
        }

        return $this->render('orthophoniste/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/orthophoniste/compte/{id}', name: 'app_orthophoniste_compte_show')]
    public function showOrthophonisteDetails(Orthophoniste $orthophoniste): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Patient && !$user instanceof Administrateur) {
            return $this->redirectToRoute('app_default');
        }

        if ($user instanceof Patient && !$user->getChoixOrtho()) {
            return $this->redirectToRoute('choisir_orthophoniste');
        }

        return $this->render('orthophoniste/show.html.twig', [
            'orthophoniste' => $orthophoniste,
            'patient' => $user instanceof Patient ? $user : null,
        ]);
    }

    #[Route('/orthophoniste/{id}', name: 'app_orthophoniste_show')]
    public function showOrthophoniste(Orthophoniste $orthophoniste): Response
    {
        return $this->render('orthophoniste/show.html.twig', [
            'orthophoniste' => $orthophoniste,
        ]);
    }

    #[Route('/orthophoniste/selectionner-orthophoniste/{id}', name: 'selectionner_orthophoniste')]
    public function selectionnerOrthophoniste(int $id, EntityManagerInterface $manager, OrthophonisteRepository $orthophonisteRepository, PatientRepository $patientRepository, ExerciceRepository $exerciceRepository, ResultatExerciceRepository $resultatExerciceRepository): Response
    {
        $patient = $this->getUser();

        if ($patient instanceof Patient) {
            $ancien = $patient->getChoixOrtho();

            if ($ancien) {
                $exercices = $patient->getExercices();
                foreach ($exercices as $exercice) {
                    $resultats = $resultatExerciceRepository->findBy(['exercice' => $exercice]);
                    foreach ($resultats as $resultat) {
                        $manager->remove($resultat);
                    }
                    $manager->remove($exercice);
                }
            }
            $orthophoniste = $orthophonisteRepository->find($id);
            $patient->setChoixOrtho($orthophoniste);

            $manager->persist($patient);
            $manager->flush();

            $this->addFlash('success', 'Orthophoniste sélectionné avec succès !');
        }

        return $this->redirectToRoute('app_espace_patient');
    }

    #[Route('/orthophoniste/special/surdite', name: 'app_orthophoniste_surdite')]
    public function specialisationSurdite(Request $request, PaginatorInterface $paginator, OrthophonisteRepository $orthophonisteRepository)
    {
        $query = $orthophonisteRepository->SpecialisationSurdite();
        $orthophonistes = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $orthophonistes = $orthophonisteRepository->searchOrthophonistes($query);
            $orthophonistes = $paginator->paginate(
                $orthophonistes,
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('orthophoniste/index.html.twig', [
            'orthophonistes' => $orthophonistes,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/orthophoniste/special/communication', name: 'app_orthophoniste_communication')]
    public function specialisationCommunication(Request $request, PaginatorInterface $paginator, OrthophonisteRepository $orthophonisteRepository)
    {
        $query = $orthophonisteRepository->SpecialisationCommunication();
        $orthophonistes = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $orthophonistes = $orthophonisteRepository->searchOrthophonistes($query);
            $orthophonistes = $paginator->paginate(
                $orthophonistes,
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('orthophoniste/index.html.twig', [
            'orthophonistes' => $orthophonistes,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/orthophoniste/special/ecriture', name: 'app_orthophoniste_ecriture')]
    public function specialisationEcriture(Request $request, PaginatorInterface $paginator, OrthophonisteRepository $orthophonisteRepository)
    {
        $query = $orthophonisteRepository->SpecialisationEcriture();
        $orthophonistes = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $orthophonistes = $orthophonisteRepository->searchOrthophonistes($query);
            $orthophonistes = $paginator->paginate(
                $orthophonistes,
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('orthophoniste/index.html.twig', [
            'orthophonistes' => $orthophonistes,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/orthophoniste/{id}/edit', name: 'app_orthophoniste_edit')]
    public function editOrthophoniste(Request $request, EntityManagerInterface $manager, Orthophoniste $orthophoniste): Response
    {
        $form = $this->createForm(OrthophonisteType::class, $orthophoniste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orthophoniste->setRoles(['ROLE_ADMIN']);
            $orthophoniste->setPassword($this->passwordHasher->hashPassword($orthophoniste, $orthophoniste->getPassword()));
            $images = $form->get('imagesOrthophoniste')->getData();
            if ($images) {
                foreach ($orthophoniste->getImagesOrthophoniste() as $existingImage) {
                    $image = $this->getParameter('images_directory') . '/' . $existingImage->getName();
                    if (file_exists($image)) {
                        unlink($image);
                    }
                    $orthophoniste->removeImagesOrthophoniste($existingImage);
                    $manager->remove($existingImage);
                }
                foreach ($images as $image) {
                    if ($image instanceof UploadedFile) {
                        $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                        $image->move(
                            $this->getParameter('images_directory'),
                            $fichier,
                        );
                        $img = new Image();
                        $img->setName($fichier);
                        $orthophoniste->addImagesOrthophoniste($img);
                    }
                }
            }
            $manager->persist($orthophoniste);
            $manager->flush();
            return $this->redirectToRoute('app_orthophoniste');
        }

        return $this->render('orthophoniste/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/orthophoniste/{id}/remove-patient', name: 'app_orthophoniste_remove_patient', methods: ['POST'])]
    public function removePatient(int $id, AdministrateurRepository $administrateurRepository, OrthophonisteRepository $orthophonisteRepository, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur actuel, qu'il soit Patient ou Administrateur

        // Vérifie si l'utilisateur est soit Patient, soit Administrateur
        if (!$user instanceof Patient && !$user instanceof Administrateur) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        $orthophoniste = $orthophonisteRepository->find($id);

        if (!$orthophoniste) {
            throw $this->createNotFoundException('Orthophoniste non trouvé.');
        }

        if ($user instanceof Patient && $user->getChoixOrtho() !== $orthophoniste) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        if ($user instanceof Patient) {
            $orthophoniste->removePatient($user);
            $user->setChoixOrtho(null);
        }

        $manager->flush();

        $this->addFlash('success', 'Orthophoniste retiré avec succès !');
        return $this->redirectToRoute('app_espace_patient');
    }


    #[Route('/orthophoniste/{id}/delete', name: 'app_orthophoniste_delete', methods: ['POST'])]
    public function delete(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        OrthophonisteRepository $orthophonisteRepository
    ): Response {
        $token = $request->request->get('_token');

        if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete' . $id, $token))) {
            $this->addFlash('error', 'Token CSRF invalide. Action annulée.');
            return $this->redirectToRoute('app_orthophoniste');
        }

        $orthophoniste = $orthophonisteRepository->find($id);

        // Vérifier si l'orthophoniste existe
        if (!$orthophoniste) {
            $this->addFlash('error', "L'orthophoniste avec l'ID $id n'existe pas ou a déjà été supprimé.");
            return $this->redirectToRoute('app_orthophoniste');
        }

        $entityManager->remove($orthophoniste);
        $entityManager->flush();

        // Ajout d'un message flash de confirmation
        $this->addFlash('success', 'Orthophoniste supprimé avec succès.');

        return $this->redirectToRoute('app_orthophoniste');
    }
}
