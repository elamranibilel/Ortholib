<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Seances;
use App\Entity\Orthophoniste;
use App\Repository\SeancesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

final class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'app_planning')]
    public function index(EntityManagerInterface $manager, SeancesRepository $seancesRepository): Response
    {
        $orthophoniste = $this->getUser();

        if (!$orthophoniste instanceof Orthophoniste) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        $seances = $seancesRepository->findBy(['orthophoniste' => $orthophoniste]);

        $patients = $manager->getRepository(Patient::class)->findBy(['choixOrtho' => $orthophoniste]);

        return $this->render('planning/index.html.twig', [
            'seances' => $seances,
            'patients' => $patients,
        ]);
    }

    #[Route('/planning/patient/{id}', name: 'app_planning_patient_show')]
    public function indexPatient(): Response
    {
        $patient = $this->getUser();

        if (!$patient instanceof Patient) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        $seances = $patient->getSeances();

        return $this->render('planning/index.html.twig', [
            'seances' => $seances,
            'patients' => $patient,
        ]);
    }

    #[Route('/planning/ajouter-seance', name: 'ajouter_seance', methods: ['POST'])]
    public function ajouterSeance(Request $request, Security $security, SerializerInterface $serialize, EntityManagerInterface $manager): JsonResponse
    {
        if (!$security->isGranted('ROLE_ORTHO')) {
            return new JsonResponse(['success' => false, 'message' => 'Accès interdit'], 403);
        }

        // get all seances per orthophoniste
        $orthophoniste = $this->getUser();
        $seances = $manager->getRepository(Seances::class)->findBy(['orthophoniste' => $orthophoniste]);

        //dd($seances);

        $ajoutSeance = $request->getContent();
        $data = json_decode($ajoutSeance, true);

        $patient = $manager->getRepository(Patient::class)->find($data['patient']);
        if (!$patient) {
            return new JsonResponse(['success' => false, 'message' => 'Patient introuvable'], 400);
        }

        $orthophoniste = $this->getUser();
        if (!$orthophoniste instanceof Orthophoniste) {
            return new JsonResponse(['success' => false, 'message' => 'Accès interdit'], 403);
        }

        $seance = new Seances();
        $seance->setOrthophoniste($orthophoniste);
        $seance->setPatient($patient);
        $seance->setDateHeureDebut(new \DateTime($data['dateHeureDebut']));
        $seance->setDateHeureFin(new \DateTime($data['dateHeureFin']));
        $seance->setMode($data['mode']);
        $seance->setExercices($data['exercices'] ?? null);


        $manager->persist($seance);
        $manager->flush();


        return $this->json(['success' => true, 'id' => $seance->getId(), 'seances' => $seances]);



        //return new JsonResponse(['success' => true]);
    }
}
