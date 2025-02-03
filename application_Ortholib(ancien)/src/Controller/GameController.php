<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Level;
use App\Entity\Score;
use App\Repository\GameRepository;
use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    #[Route('/games', name: 'game_list')]
    public function listGames(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();
        return $this->render('game/list.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/games/{id}', name: 'game_show')]
    public function showGame(Game $game): Response
    {
        // Récupère le premier niveau pour l'afficher, s'il existe
        $level = $game->getLevel()->first() ?: null;

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'level' => $level,
        ]);
    }

    #[Route('/games/{id}/submit', name: 'game_submit', methods: ['POST'])]
    public function submitGame(Request $request, Game $game, ScoreRepository $scoreRepository, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour soumettre un score.');
            return $this->redirectToRoute('app_login');
        }

        // Récupère le score depuis la requête
        $submittedScore = $request->request->getInt('score', 0);
        if ($submittedScore <= 0) {
            $this->addFlash('error', 'Le score soumis est invalide.');
            return $this->redirectToRoute('game_show', ['id' => $game->getId()]);
        }

        // Création et persistance du nouveau score
        $score = new Score();
        $score->setUser($user);
        $score->setGame($game);
        $score->setScore($submittedScore);

        $entityManager->persist($score);
        $entityManager->flush();

        $this->addFlash('success', 'Votre score a été enregistré avec succès.');
        return $this->redirectToRoute('game_list');
    }
}
