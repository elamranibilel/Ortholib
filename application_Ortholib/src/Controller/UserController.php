<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Patient;
use App\Entity\Orthophoniste;
use App\Form\UserType;
use App\Form\PatientType;
use App\Form\OrthophonisteType;
use App\Repository\UserRepository;
use App\Repository\PatientRepository;
use App\Repository\OrthophonisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/admin')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    private function listEntities($repository, Request $request, PaginatorInterface $paginator, string $view): Response
    {
        $query = $repository->findAll();
        $entities = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render($view, ['entities' => $entities]);
    }

    // User Management
    #[Route('/user', name: 'app_user')]
    public function indexUsers(Request $request, PaginatorInterface $paginator, UserRepository $userRepo): Response
    {
        return $this->listEntities($userRepo, $request, $paginator, 'user/index.html.twig');
    }

    #[Route('/user/new', name: 'app_user_new')]
    public function newUser(Request $request, EntityManagerInterface $manager, ValidatorInterface $validator): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/user/{id}', name: 'app_user_show')]
    public function showUser(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function editUser(Request $request, EntityManagerInterface $manager, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/edit.html.twig', ['formCreateUtilisateur' => $form->createView()]);
    }

    #[Route('user/trie/id', name: 'app_user_trie_id', methods: ['GET'])]
    public function trieUtilisateurID(Request $request, PaginatorInterface $paginator, UtilisateurRepository $utilisateurRepo): Response
    {
        $query = $utilisateurRepo->trieUtilisateurID();
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('utilisateur/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('user/trie/nom', name: 'app_user_trie_nom', methods: ['GET'])]
    public function trieUtilisateurNom(Request $request, PaginatorInterface $paginator, UtilisateurRepository $utilisateurRepo): Response
    {
        $query = $utilisateurRepo->trieUtilisateurNom();
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('utilisateur/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/delete/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function deleteUser(int $id, Request $request, EntityManagerInterface $manager, CsrfTokenManagerInterface $csrfManager, UserRepository $userRepo): Response
    {
        $token = $request->request->get('_token');

        if (!$csrfManager->isTokenValid(new CsrfToken('delete' . $id, $token))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $user = $userRepo->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('app_user');
    }

    // Patient Management
    #[Route('/patient', name: 'app_patient')]
    public function indexPatients(Request $request, PaginatorInterface $paginator, PatientRepository $patientRepo): Response
    {
        return $this->listEntities($patientRepo, $request, $paginator, 'patient/index.html.twig');
    }

    #[Route('/patient/new', name: 'app_patient_new')]
    public function newPatient(Request $request, EntityManagerInterface $manager): Response
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($patient);
            $manager->flush();
            return $this->redirectToRoute('app_patient');
        }

        return $this->render('patient/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/patient/{id}', name: 'app_patient_show')]
    public function showPatient(Patient $patient): Response
    {
        return $this->render('patient/show.html.twig', ['patient' => $patient]);
    }

    #[Route('/patient/edit/{id}', name: 'app_patient_edit')]
    public function editPatient(Request $request, EntityManagerInterface $manager, Patient $patient): Response
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_patient');
        }

        return $this->render('patient/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/patient/delete/{id}', name: 'app_patient_delete', methods: ['POST'])]
    public function deletePatient(
        int $id,
        Request $request,
        EntityManagerInterface $manager,
        CsrfTokenManagerInterface $csrfManager,
        PatientRepository $patientRepo
    ): Response {
        $token = $request->request->get('_token');

        if (!$csrfManager->isTokenValid(new CsrfToken('delete' . $id, $token))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $patient = $patientRepo->find($id);
        if (!$patient) {
            throw $this->createNotFoundException('Patient not found');
        }

        $manager->remove($patient);
        $manager->flush();

        return $this->redirectToRoute('app_patient');
    }

    // Orthophoniste Management
    #[Route('/orthophoniste', name: 'app_orthophoniste')]
    public function indexOrthophonistes(Request $request, PaginatorInterface $paginator, OrthophonisteRepository $orthophonisteRepo): Response
    {
        return $this->listEntities($orthophonisteRepo, $request, $paginator, 'orthophoniste/index.html.twig');
    }

    #[Route('/orthophoniste/new', name: 'app_orthophoniste_new')]
    public function newOrthophoniste(Request $request, EntityManagerInterface $manager): Response
    {
        $orthophoniste = new Orthophoniste();
        $form = $this->createForm(OrthophonisteType::class, $orthophoniste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($orthophoniste);
            $manager->flush();
            return $this->redirectToRoute('app_orthophoniste');
        }

        return $this->render('orthophoniste/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/orthophoniste/{id}', name: 'app_orthophoniste_show')]
    public function showOrthophoniste(Orthophoniste $orthophoniste): Response
    {
        return $this->render('orthophoniste/show.html.twig', ['orthophoniste' => $orthophoniste]);
    }

    #[Route('/orthophoniste/edit/{id}', name: 'app_orthophoniste_edit')]
    public function editOrthophoniste(Request $request, EntityManagerInterface $manager, Orthophoniste $orthophoniste): Response
    {
        $form = $this->createForm(OrthophonisteType::class, $orthophoniste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_orthophoniste');
        }

        return $this->render('orthophoniste/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
