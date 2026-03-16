<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Orthophoniste;
use App\Entity\Patient;
use App\Form\CommentaireType;
use App\Repository\PatientRepository;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use App\Repository\OrthophonisteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ResultatExerciceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ORTHO')]
final class EspaceOrthophonisteController extends AbstractController
{
    private OrthophonisteRepository $orthophonisteRepository;
    private PaginatorInterface $paginator;

    public function __construct(OrthophonisteRepository $orthophonisteRepository, PaginatorInterface $paginator)
    {
        $this->orthophonisteRepository = $orthophonisteRepository;
        $this->paginator = $paginator;
    }

    #[Route('/espace/orthophoniste', name: 'app_espace_orthophoniste')]
    public function index(PatientRepository $patientRepository): Response
    {
        $orthophoniste = $this->getUser();
        if (!$orthophoniste instanceof Orthophoniste) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        $patientsAttente = $patientRepository->findBy([
            'choixOrtho' => $orthophoniste,
            'isConfirmed' => false,
        ]);

        return $this->render('espace_orthophoniste/index.html.twig', [
            'orthophoniste' => $orthophoniste,
            'patientsEnAttente' => $patientsAttente,
        ]);
    }

    #[Route('/espace/orthophoniste/confirmation/{id}', name: 'app_espace_orthophoniste_confirmation')]
    public function confirmation(Patient $patient, EntityManagerInterface $entityManager): Response
    {
        $orthophoniste = $this->getUser();
        if (!$orthophoniste instanceof Orthophoniste && $patient->getChoixOrtho() !== $orthophoniste) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        $patient->setIsConfirmed(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_espace_orthophoniste');
    }

    #[Route('/espace/orthophoniste/choix-exercices', name: 'app_espace_orthophoniste_choix')]
    public function exerciceTypePatient(): Response
    {
        return $this->render('espace_orthophoniste/exerciceTypePatient.html.twig');
    }

    #[Route('/espace/orthophoniste/choix-patient', name: 'app_espace_orthophoniste_choixPatient')]
    public function exerciceChoixPatient(): Response
    {
        return $this->render('espace_orthophoniste/DetailsOrEspace.html.twig');
    }

    #[Route('/espace/orthophoniste/patient', name: 'app_espace_orthophoniste_patient')]
    public function indexPatient(Request $request): Response
    {
        $orthophoniste = $this->getUser();
        if (!$orthophoniste instanceof Orthophoniste) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        $query = $orthophoniste->getPatients();
        $patients = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        // Avant de rendre dans l'espace Orthophoniste, on doit choisir un patient pour voir des séances
        // Ou regarder les séances de tous les patients 
        // Grâce au planning
        // Voir des exercices et à faire des exercices (à voir plus tard sur les dates limites)
        // Voir les statistiques des patients
        // Voir les statistiques des séances 

        //$seances = $orthophoniste->getSeances();
        return $this->render('espace_orthophoniste/listePatient.html.twig', [
            'patients' => $patients,
            /* 'seances' => $seances, */
        ]);
    }

    #[Route('/espace/orthophoniste/patient/{id}', name: 'app_espace_orthophoniste_patient_show')]
    public function indexPatientShow($id, PatientRepository $patientRepository, CommentaireRepository $commentaireRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $orthophoniste = $this->getUser();

        if (!$orthophoniste instanceof Orthophoniste) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        $patient = $patientRepository->find($id);
        if (!$patient) {
            throw $this->createNotFoundException("Patient introuvable");
        }

        if ($patient->getChoixOrtho() !== $orthophoniste) {
            throw $this->createAccessDeniedException("Ce patient ne vous appartient pas.");
        }

        $commentaires = $commentaireRepository->findBy([
            'auteur' => $patient,
            'destinataire' => $orthophoniste
        ]);

        $nouveauCommentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $nouveauCommentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nouveauCommentaire->setAuteur($orthophoniste);
            $nouveauCommentaire->setDestinataire($patient);
            $nouveauCommentaire->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($nouveauCommentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_espace_orthophoniste_patient_show', ['id' => $patient->getId()]);
        }

        return $this->render('espace_orthophoniste/espace.html.twig', [
            'patient' => $patient,
            'orthophoniste' => $orthophoniste,
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/espace/orthophoniste/patient/{id}/seances', name: 'app_espace_orthophoniste_seances')]
    public function indexSeances($id, PatientRepository $patientRepository): Response
    {
        $orthophoniste = $this->getUser();
        if (!$orthophoniste instanceof Orthophoniste) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        $patient = $patientRepository->find($id);
        if (!$patient) {
            throw $this->createNotFoundException("Patient introuvable");
        }

        $seances = $patient->getSeances();

        return $this->render('espace_orthophoniste/seances.html.twig', [
            'seances' => $seances,
        ]);
    }

    #[Route('/espace/orthophoniste/exercice', name: 'app_espace_orthophoniste_exercices')]
    public function indexExercices(ExerciceRepository $exerciceRepository, ResultatExerciceRepository $resultatExerciceRepository): Response
    {
        $orthophoniste = $this->getUser();
        if (!$orthophoniste instanceof Orthophoniste) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        $exercices = $exerciceRepository->findBy(
            ['orthophoniste' => $orthophoniste],
        );

        $scores = [];
        $exercicesParPatient = [];
        foreach ($exercices as $exercice) {
            $scores[$exercice->getId()] = $resultatExerciceRepository->findLast3ResultsByExercice($exercice->getId());
            $exercicesParPatient[$exercice->getPatient()->getId()][] = $exercice;
        }

        return $this->render('exercice/index.html.twig', [
            'exercices' => $exercices,
            'scores' => $scores,
            'exercicesParPatient' => $exercicesParPatient,
        ]);
    }

    #[Route('/espace/orthophoniste/exercice/patient/{id}', name: 'app_espace_orthophoniste_exercices_patient')]
    public function indexExercicesPatient($id, ExerciceRepository $exerciceRepository, ResultatExerciceRepository $resultatExerciceRepository): Response
    {
        $orthophoniste = $this->getUser();
        if (!$orthophoniste instanceof Orthophoniste) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        $exercices = $exerciceRepository->findBy(
            ['orthophoniste' => $orthophoniste, 'patient' => $id],
        );

        $scores = [];
        $exercicesParPatient = [];
        foreach ($exercices as $exercice) {
            $scores[$exercice->getId()] = $resultatExerciceRepository->findLast3ResultsByExercice($exercice->getId());
            $exercicesParPatient[$exercice->getPatient()->getId()][] = $exercice;
        }

        return $this->render('exercice/index.html.twig', [
            'exercices' => $exercices,
            'scores' => $scores,
            'exercicesParPatient' => $exercicesParPatient,
        ]);
    }
}
