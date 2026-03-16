<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Exercice;
use App\Entity\Commentaire;
use App\Entity\Orthophoniste;
use App\Form\CommentaireType;
use App\Entity\Administrateur;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\PatientRepository;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use App\Repository\OrthophonisteRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ResultatExerciceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace/patient')]
final class EspacePatientController extends AbstractController
{
    #[Route('/', name: 'app_espace_patient')]
    public function index(PatientRepository $patientRepository, ResultatExerciceRepository $resultatExerciceRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $user = $this->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        if ($user instanceof Orthophoniste) {
            return $this->redirectToRoute('app_espace_orthophoniste');
        } elseif ($user instanceof Administrateur) {
            return $this->redirectToRoute('app_administrateur');
        }

        $patient = $patientRepository->find($user);
        if (!$patient) {
            return $this->redirectToRoute('app_login');
        }

        if (!$patient->getChoixOrtho() || !$patient->isConfirmed()) {
            return $this->redirectToRoute('choisir_orthophoniste');
        }

        $resultats = $resultatExerciceRepository->findBy(['patient' => $patient], ['date' => 'ASC']);

        $labels = [];
        $scores = [];

        foreach ($resultats as $resultat) {
            $labels[] = $resultat->getDate()->format('d/m/Y');
            $scores[] = $resultat->getScore();
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Scores',
                    'data' => $scores,
                    'backgroundColor' => '#4CAF50',
                    'borderColor' => '#4CAF50',
                ],
            ],
        ]);
        $chart->setOptions([
            'scales' => [
                'y' => ['suggestedMin' => 0, 'suggestedMax' => 100],
            ]
        ]);

        dump($labels, $scores);
        return $this->render('espace_patient/liste.html.twig', [
            'patient' => $patient,
            'chart' => $chart,
        ]);
    }

    #[Route('/exercices', name: 'app_espace_patient_exercices')]
    public function listeExercices(PatientRepository $patientRepository, ResultatExerciceRepository $resultatExerciceRepository): Response
    {
        $user = $this->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_ORTHO')) {
            return $this->redirectToRoute('app_espace_orthophoniste');
        }

        $patient = $patientRepository->find($user);
        if (!$patient) {
            return $this->redirectToRoute('app_login');
        }

        if (!$patient->getChoixOrtho()) {
            return $this->redirectToRoute('choisir_orthophoniste');
        }

        // Récupération des exercices de l'orthophoniste choisi
        $nouvelOrthophoniste = $patient->getChoixOrtho();
        $exercices = array_filter($patient->getExercices()->toArray(), function ($exercice) use ($nouvelOrthophoniste) {
            return $exercice->getOrthophoniste() === $nouvelOrthophoniste;
        });

        $historique = [];
        foreach ($exercices as $exercice) {
            $historique[$exercice->getId()] = $resultatExerciceRepository->findLast3ResultsByExercice($exercice->getId());
        }
        return $this->render('espace_patient/exercices.html.twig', [
            'exercices' => $exercices,
            'historique' => $historique,
        ]);
    }

    #[Route('/show/{id}', name: 'app_espace_patient_show')]
    public function show(Request $request, Patient $patient, CommentaireRepository $commentaireRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('view_patient', $patient);

        $orthophoniste = $patient->getChoixOrtho();

        $commentaire = $commentaireRepository->findBy(['auteur' => $orthophoniste, 'destinataire' => $patient]);

        $nouveauCommentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $nouveauCommentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nouveauCommentaire->setAuteur($patient);
            $nouveauCommentaire->setDestinataire($orthophoniste);
            $nouveauCommentaire->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($nouveauCommentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_espace_patient_show', ['id' => $patient->getId()]);
        }
        return $this->render('espace_patient/espace.html.twig', [
            'patient' => $patient,
            'orthophoniste' => $orthophoniste,
            'commentaires' => $commentaire,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/show/exercice/{id}', name: 'app_espace_patient_show_exercice')]
    public function showExercice($id, ExerciceRepository $exerciceRepository, ResultatExerciceRepository $resultatExerciceRepository): Response
    {
        $exercice = $exerciceRepository->find($id);

        $historique = $resultatExerciceRepository->findLast10ResultsByExercice($id);
        return $this->render('espace_patient/show_exercice.html.twig', [
            'exercice' => $exercice,
            'historique' => $historique,
        ]);
    }

    #[Route('/show/resultat/{id}', name: 'app_espace_patient_show_resultat')]
    public function showResultat(Exercice $exercice): Response
    {
        $resultats = $exercice->getResultatExercices();
        return $this->render('espace_patient/show_resultat.html.twig', [
            'resultats' => $resultats,
        ]);
    }
}
