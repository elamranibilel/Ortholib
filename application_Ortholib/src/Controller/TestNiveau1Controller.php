<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\ChoiceRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

#[Route('/test-niveau1')]
class TestNiveau1Controller extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_test_niveau1_index')]
    public function index(QuestionRepository $questionRepository): Response
    {
        $questions = $questionRepository->findBy(['level' => 1]);

        return $this->render('test_niveau1/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/{level}', name: 'app_quiz', requirements: ['level' => '\d+'])]
    public function indexTestNiv_1(int $level, QuestionRepository $questionRepository): Response
    {
        $questions = $questionRepository->findBy(['level' => $level]);

        if (!$questions) {
            throw $this->createNotFoundException('No questions found for this level');
        }

        return $this->render('test_niveau1/indexTestNiv_1.html.twig', [
            'questions' => $questions,
            'level' => $level,
        ]);
    }

    #[Route('/check/{level}', name: 'quiz_check', methods: ['GET','POST'])]
    public function check(int $level, Request $request, ChoiceRepository $choiceRepository): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $data = $request->request->all();
        $score = 0;

        foreach ($data as $questionId => $choiceId) {
            $choice = $choiceRepository->find($choiceId);
            if ($choice && $choice->isIsCorrect()) {
                $score++;
            }
        }

        $total = count($data);
        $nextLevel = $level + 1;

        // Mettre à jour le score et le niveau le plus élevé de l'utilisateur
        $user->setScore($user->getScore() + $score);
        if ($score >= $total / 2 && $nextLevel > ($user->getHightLevel() ?? 0)) {
            $user->setHightLevel($nextLevel);
        }

        // Enregistrer les changements dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Rediriger vers le niveau suivant si l'utilisateur a réussi
        if ($score >= $total / 2 && $nextLevel <= 3) {
            return $this->redirectToRoute('app_quiz', ['level' => $nextLevel]);
        }

        // Afficher les résultats du quiz
        return $this->render('test_niveau1/result.html.twig', [
            'score' => $score,
            'total' => $total,
            'level' => $level,
            'passed' => $score >= $total / 2,
        ]);
    }
}
