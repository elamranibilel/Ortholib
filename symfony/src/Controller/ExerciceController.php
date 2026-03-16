<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Entity\Signification;
use App\Entity\ResultatExercice;
use App\Repository\PatientRepository;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ResultatExerciceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExerciceController extends AbstractController
{
    private Security $security;
    private PaginatorInterface $paginator;

    public function __construct(Security $security, PaginatorInterface $paginator)
    {
        $this->security = $security;
        $this->paginator = $paginator;
    }

    #[Route('/exercice', name: 'app_exercice')]
    public function index(ExerciceRepository $exerciceRepository, ResultatExerciceRepository $resultatExerciceRepository): Response
    {
        $exercices = $exerciceRepository->findAll();

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

    #[Route('/exercice/type', name: 'app_exercice_type')]
    public function indexType(): Response
    {
        return $this->render('exercice/indexType.html.twig');
    }

    #[Route('/exercice/new', name: 'app_exercice_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        if ($this->security->isGranted('ROLE_ORTHO')) {
            $orthophoniste = $this->getUser();
            $exercice = new Exercice();
            $form = $this->createForm(ExerciceType::class, $exercice);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $exercice->setOrthophoniste($orthophoniste);
                $manager->persist($exercice);
                $manager->flush();

                return $this->redirectToRoute('app_exercice_show', ['id' => $exercice->getId()]);
            }
        } else {
            $exercice = new Exercice();
            $form = $this->createForm(ExerciceType::class, $exercice);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($exercice);
                $manager->flush();

                return $this->redirectToRoute('app_exercice_show', ['id' => $exercice->getId()]);
            }
        }

        return $this->render('exercice/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/exercice/{id}', name: 'app_exercice_show')]
    public function show(int $id, ExerciceRepository $exerciceRepository): Response
    {
        $exercice = $exerciceRepository->find($id);

        if (!$exercice) {
            throw $this->createNotFoundException('L\'exercice demandé n\'existe pas.');
        }

        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
        ]);
    }

    #[Route('/exercice/{id}/faire/vocabulaire', name: 'app_exercice_faire_vocabulaire')]
    public function faireExerciceVocabulaire($id, Request $request, ExerciceRepository $exerciceRepository, ResultatExerciceRepository $resultatExerciceRepository)
    {
        $exercice = $exerciceRepository->find($id);
        if (!$exercice) {
            throw $this->createNotFoundException("Exercice non trouvé.");
        }

        $user = $this->getUser();
        if ($user instanceof Patient) {
            // Récupérer le dernier résultat de l'exercice pour afficher le score
            $resultatExercice = $resultatExerciceRepository
                ->findOneBy(['patient' => $user, 'exercice' => $exercice], ['date' => 'DESC']);

            if ($resultatExercice) {
                return $this->render('exercice/faire.html.twig', [
                    'exercice' => $exercice,
                    'significations' => $exercice->getSignification(),
                ]);
            }
        }

        return $this->render('exercice/faire.html.twig', [
            'exercice' => $exercice,
            'significations' => $exercice->getSignification(),
        ]);
    }

    #[Route('/exercice/{id}/faire/memoire', name: 'app_exercice_faire_memoire')]
    public function faireExerciceMemoire(
        $id,
        Request $request,
        ExerciceRepository $exerciceRepository,
        ResultatExerciceRepository $resultatExerciceRepository,
        EntityManagerInterface $entityManager
    ) {
        $exercice = $exerciceRepository->find($id);
        if (!$exercice) {
            throw $this->createNotFoundException("Exercice non trouvé.");
        }

        $user = $this->getUser();
        $dernierScore = null;

        if ($user instanceof Patient) {
            $resultatExercice = $resultatExerciceRepository
                ->findOneBy(['patient' => $user, 'exercice' => $exercice], ['date' => 'DESC']);
            $dernierScore = $resultatExercice ? $resultatExercice->getScore() : null;
        }

        // Récupération des paires à associer
        $significations = $exercice->getSignification()->toArray();
        $cartes = [];

        foreach ($significations as $signification) {
            $cartes[] = ['type' => 'mot', 'valeur' => $signification->getMots()];
            /*             $cartes[] = ['type' => 'image', 'valeur' => $signification->getImages()];
 */
        }

        shuffle($cartes); // Mélanger les cartes avant de les envoyer à la vue

        if ($request->isMethod('POST')) {
            // Traitement de la réponse de l'utilisateur
            $donnees = $request->request->all();
            $reponses = $donnees['paires'] ?? [];

            // Vérifier si $reponses est une chaîne JSON
            if (is_string($reponses)) {
                $reponses = json_decode($reponses, true);
            }

            // Vérification pour éviter une boucle sur une valeur incorrecte
            if (!is_array($reponses)) {
                $reponses = [];
            }

            $score = 0;
            foreach ($reponses as $reponse) {
                if ($this->verifierCorrespondance($reponse)) {
                    $score++;
                }
            }

            // Sauvegarde du résultat en base de données
            $resultat = new ResultatExercice();
            $resultat->setPatient($user);
            $resultat->setExercice($exercice);
            $resultat->setScore($score);
            $resultat->setDate(new \DateTime());

            $entityManager->persist($resultat);
            $entityManager->flush();

            return $this->redirectToRoute('app_exercice_faire_memoire', ['id' => $id]);
        }

        return $this->render('exercice/memoire.html.twig', [
            'exercice' => $exercice,
            'cartes' => $cartes,
            'dernierScore' => $dernierScore,
        ]);
    }

    /**
     * Vérifie si une paire est correcte
     */
    private function verifierCorrespondance($paire)
    {
        return $paire['mot'] === $paire['image']; // Exemple de vérification (adapter selon ton modèle)
    }

    private function calculerScore(Request $request, Exercice $exercice)
    {
        $associations = json_decode($request->get('associations'), true);
        dump($associations);

        $score = 0;
        $total = count($exercice->getSignification()); // Nombre total de définitions
        /*         dump($exercice->getSignification()); die;
 */

        foreach ($associations as $association) {
            // Vérifier si une signification avec cet ID existe
            $significationTrouvee = $exercice->getSignification()->filter(function ($signif) use ($association) {
                return $signif->getId() == $association['word'] && $signif->getId() == $association['definition'];
            })->first();

            if ($significationTrouvee) {
                $score++;
            }
        }

        // Calculer le pourcentage
        return ($score / $total) * 100;
    }

    #[Route('/exercice/{id}/verifier', name: 'app_exercice_verifier', methods: ['POST'])]
    public function verifierExercice($id, Request $request, ExerciceRepository $exerciceRepository, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        if (!$user instanceof Patient) {
            return $this->redirectToRoute('app_default');
        }

        $exercice = $exerciceRepository->find($id);
        if (!$exercice) {
            throw $this->createNotFoundException("Exercice non trouvé.");
        }

        // Calcul du score basé sur les réponses de l'utilisateur
        $score = $this->calculerScore($request, $exercice);

        // Enregistrer le résultat dans ResultatExercice
        $resultatExercice = new ResultatExercice();
        $resultatExercice->setPatient($user);
        $resultatExercice->setExercice($exercice);
        $resultatExercice->setScore($score);
        $resultatExercice->setDate(new \DateTime());

        $entityManager->persist($resultatExercice);
        $entityManager->flush();

        // Passer le score au template pour affichage
        return $this->render('exercice/resultat.html.twig', [
            'exercice' => $exercice,
            'score' => $score
        ]);
    }

    #[Route('/exercice/{id}/edit', name: 'app_exercice_edit')]
    public function edit(Request $request, Exercice $exercice, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($exercice);
            $manager->flush();

            return $this->redirectToRoute('app_exercice_show', ['id' => $exercice->getId()]);
        }

        if (!$exercice) {
            throw $this->createNotFoundException('L\'exercice demandé n\'existe pas.');
        }

        return $this->render('exercice/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/exercice/type/vocabulaire', name: 'app_exercice_type_vocabulaire')]
    public function typeVocabulaire(ExerciceRepository $exerciceRepository, ResultatExerciceRepository $resultatExerciceRepository): Response
    {
        $exercices = $exerciceRepository->exerciceTypeVocabulaire();

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

    #[Route('/exercice/type/orthographe', name: 'app_exercice_type_orthographe')]
    public function typeOrthographe(ExerciceRepository $exerciceRepository, ResultatExerciceRepository $resultatExerciceRepository): Response
    {
        $exercices = $exerciceRepository->exerciceTypeOrthographe();

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

    #[Route('/exercice/type/memoire', name: 'app_exercice_type_memoire')]
    public function typeMemoire(ExerciceRepository $exerciceRepository, ResultatExerciceRepository $resultatExerciceRepository): Response
    {
        $exercices = $exerciceRepository->exerciceTypeMemoire();

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

    #[Route('/exercice/{id}/signification', name: 'app_exercice_signification')]
    public function signification(int $id, ExerciceRepository $exerciceRepository): Response
    {
        $exercice = $exerciceRepository->find($id);
        $significations = $exercice->getContenu();

        if (!$exercice) {
            throw $this->createNotFoundException('L\'exercice demandé n\'existe pas.');
        }

        return $this->render('exercice/signification.html.twig', [
            'exercice' => $exercice,
            'significations' => $significations,
        ]);
    }
}
