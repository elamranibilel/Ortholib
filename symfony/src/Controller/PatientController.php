<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Patient;
use App\Form\SearchType;
use App\Form\PatientType;
use App\Entity\Orthophoniste;
use App\Security\PatientVoter;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/* #[Route('/admin')]
 */

/* #[IsGranted("ROLE_PATIENT")] */

class PatientController extends AbstractController
{
    private PatientRepository $patientRepo;
    private $passwordHasher;

    public function __construct(PatientRepository $patientRepo, UserPasswordHasherInterface $passwordHasher)
    {
        $this->patientRepo = $patientRepo;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/patient', name: 'app_patient')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        if ($user instanceof Patient) {
            return $this->redirectToRoute('app_patient_show', ['id' => $user->getId()]);
        }

        $query = $this->patientRepo->findAll();
        $patients = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('patient/index.html.twig', [
            'patients' => $patients,
        ]);
    }

    #[Route('/patient/search', name: 'app_patient_search')]
    public function search(Request $request, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $patients = $this->patientRepo->searchPatients($query);
        } else {
            $patients = [];
        }

        return $this->render('patient/search.html.twig', [
            'patients' => $patients,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/patient/new', name: 'app_patient_new')]
    public function newPatient(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if ($user instanceof Patient) {
            return $this->redirectToRoute('app_patient'); // Rediriger vers la liste des patients
        }

        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setRoles(['ROLE_PATIENT']);
            $patient->setPassword($this->passwordHasher->hashPassword($patient, $patient->getPassword()));
            $images = $form->get('imagesPatient')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);

                $img = new Image();
                $img->setName($fichier);
                $patient->addImagesPatient($img);
            }

            $patient->setIsConfirmed(false);
            $manager->persist($patient);
            $manager->flush();
            if ($this->getUser() == null) {
                return $this->redirectToRoute('app_login');
            } else {
                return $this->redirectToRoute('app_patient');
            }
        }

        return $this->render('patient/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/patient/{id}', name: 'app_patient_show')]
    public function showPatient(Patient $patient): Response
    {
        $orthophoniste = $patient->getChoixOrtho();
        if (!$orthophoniste) {
            $this->denyAccessUnlessGranted(PatientVoter::VIEW, $patient);
        }
        return $this->render('patient/show.html.twig', [
            'patient' => $patient,
            'orthophoniste' => $orthophoniste,
        ]);
    }

    #[Route('/patient/{id}/edit', name: 'app_patient_edit')]
    public function editPatient(Request $request, EntityManagerInterface $manager, Patient $patient): Response
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('imagesPatient')->getData();
            if ($images) {
                foreach ($patient->getImagesPatient() as $existingImage) {
                    $image = $this->getParameter('images_directory') . '/' . $existingImage->getName();
                    if (file_exists($image)) {
                        unlink($image);
                    }
                    $patient->removeImagesPatient($existingImage);
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
                        $patient->addImagesPatient($img);
                    }
                }
            }
            $manager->flush();
            return $this->redirectToRoute('app_patient');
        }

        return $this->render('patient/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/patient/{id}/remove-orthophoniste', name: 'app_patient_remove_orthophoniste')]
    public function removeOrthophoniste(int $id, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Orthophoniste) {
            throw $this->createAccessDeniedException('Seuls les orthophonistes peuvent supprimer un patient.');
        }

        $patient = $this->patientRepo->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('Patient non trouvé.');
        }

        // Vérifier que le patient est bien suivi par l'orthophoniste connecté
        if ($patient->getChoixOrtho() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer un patient que vous ne suivez pas.');
        }

        // Dissocier le patient de l’orthophoniste
        $patient->setChoixOrtho(null);
        $manager->flush();

        $this->addFlash('success', 'Le patient a été retiré de votre suivi.');

        return $this->redirectToRoute('app_espace_orthophoniste');
    }


    #[Route('/patient/{id}/delete', name: 'app_patient_delete', methods: ['POST'])]
    public function deletePatient(
        int $id,
        Request $request,
        EntityManagerInterface $manager,
        CsrfTokenManagerInterface $csrfManager,
    ): Response {
        $token = $request->request->get('_token');

        if (!$csrfManager->isTokenValid(new CsrfToken('delete' . $id, $token))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $patient = $this->patientRepo->find($id);
        if (!$patient) {
            throw $this->createNotFoundException('Patient not found');
        }

        $manager->remove($patient);
        $manager->flush();

        return $this->redirectToRoute('app_patient');
    }
}
