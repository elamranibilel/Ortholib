<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Form\AdministrateurType;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrthophonisteRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\AdministrateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
class AdministrateurController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/', name: 'app_administrateur')]
    public function index(Request $request, PaginatorInterface $paginator, AdministrateurRepository $adminRepository): Response
    {
        $query = $adminRepository->findAll();
        $administrateurs = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('administrateur/index.html.twig', [
            'administrateurs' => $administrateurs,
        ]);
    }

    #[Route('/patients', name: 'app_administrateur_patients')]
    public function indexPatients(PatientRepository $patientRepository): Response
    {
        $admin = $this->getUser();

        if (!$admin instanceof Administrateur) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return $this->redirectToRoute('app_administrateur');
        }

        $patients = $patientRepository->findAll();

        return $this->render('administrateur/patients.html.twig', [
            'patients' => $patients,
            'admin' => $admin,
        ]);
    }

    // Création d'un nouvel administrateur
    #[Route('/new', name: 'app_administrateur_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $admin = new Administrateur();
        $form = $this->createForm(AdministrateurType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setPassword($this->passwordHasher->hashPassword($admin, $admin->getPassword()));
            $manager->persist($admin);
            $manager->flush();

            return $this->redirectToRoute('app_administrateur');
        }

        return $this->render('administrateur/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Affichage des détails d'un administrateur
    #[Route('/{id}', name: 'app_administrateur_show')]
    public function show(Administrateur $admin): Response
    {
        return $this->render('administrateur/show_easyadmin.html.twig', [
            'administrateur' => $admin,
        ]);
        return $this->render('administrateur/show.html.twig', [
            'administrateur' => $admin,
        ]);
    }

    // Modification d'un administrateur
    #[Route('/{id}/edit', name: 'app_administrateur_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Administrateur $admin): Response
    {
        $form = $this->createForm(AdministrateurType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_administrateur');
        }

        return $this->render('administrateur/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/remove-patient', name: 'app_administrateur_remove_patient', methods: ['POST'])]
    public function removePatient(int $id, PatientRepository $patientRepository, EntityManagerInterface $manager): Response
    {
        $admin = $this->getUser();

        if (!$admin instanceof Administrateur) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        $patient = $patientRepository->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('Patient non trouvé.');
        }

        $orthophoniste = $patient->getChoixOrtho(); // Récupérer l'orthophoniste lié

        if ($orthophoniste) {
            $patient->setChoixOrtho(null); // Dissocier le patient de l'orthophoniste
            $manager->flush();

            $this->addFlash('success', 'Le suivi a été arrêté et l\'orthophoniste a été dissocié du patient.');
        } else {
            $this->addFlash('warning', 'Ce patient n\'avait pas d\'orthophoniste associé.');
        }

        return $this->redirectToRoute('app_administrateur_patients');
    }


    #[Route('/{id}/delete', name: 'app_administrateur_delete', methods: ['POST'])]
    public function delete(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        AdministrateurRepository $adminRepository
    ): Response {
        $token = $request->request->get('_token');

        if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete' . $id, $token))) {
            $this->addFlash('error', 'Token CSRF invalide. Action annulée.');
            return $this->redirectToRoute('app_administrateur');
        }

        $admin = $adminRepository->find($id);

        if (!$admin) {
            $this->addFlash('error', "L'administrateur avec l'ID $id n'existe pas ou a déjà été supprimé.");
            return $this->redirectToRoute('app_administrateur');
        }

        $entityManager->remove($admin);
        $entityManager->flush();

        $this->addFlash('success', 'Administrateur supprimé avec succès.');

        return $this->redirectToRoute('app_administrateur');
    }

    #[Route('/{id}/delete', name: 'app_admin_delete', methods: ['POST'])]
    public function deleteAdmin(Request $request, Administrateur $admin, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $admin->getId(), $request->request->get('_token'))) {

            // 1. Déconnecter l'utilisateur AVANT de supprimer
            $this->container->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();

            // 2. Supprimer ensuite l'utilisateur
            $em->remove($admin);
            $em->flush();

            // 3. Redirection vers l’accueil
            return $this->redirectToRoute('app_home');
        }

        // En cas d’échec, retourner vers le profil
        return $this->redirectToRoute('app_profile');
    }
}
