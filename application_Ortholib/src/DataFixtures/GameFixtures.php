<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Level;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GameFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'un jeu
        $game = new Game();
        $game->setName('Jeu de Vocabulaire')
            ->setDescription('Un jeu pour améliorer votre vocabulaire.')
            ->setType('Vocabulaire');

        $manager->persist($game);

        // Ajout des niveaux au jeu
        $levels = [
            ['difficulty' => 'Facile', 'timeLimit' => null, 'successThreshold' => 90],
            ['difficulty' => 'Moyen', 'timeLimit' => 60, 'successThreshold' => 90],
            ['difficulty' => 'Difficile', 'timeLimit' => 50, 'successThreshold' => 90],
        ];

        foreach ($levels as $levelData) {
            $level = new Level();
            $level->setDifficulty($levelData['difficulty'])
                ->setTimeLimit($levelData['timeLimit'] ?? null)
                ->setSuccessThreshold($levelData['successThreshold'])
                ->setGameLevel($game);
            $manager->persist($level);
        }

        // Ajout des questions au jeu
        $questions = [
            [
                'content' => 'Relier les définitions aux mots.',
                'type' => 'definitions',
                'options' => [
                    'Mot1' => 'Définition1',
                    'Mot2' => 'Définition2',
                    'Mot3' => 'Définition3',
                ],
            ],
            [
                'content' => 'Associer les synonymes.',
                'type' => 'synonyms',
                'options' => [
                    'Rapide' => 'Vite',
                    'Lent' => 'Paresseux',
                    'Petit' => 'Minuscule',
                ],
            ],
        ];

        foreach ($questions as $questionData) {
            $question = new Question();
            $question->setContent($questionData['content'])
                ->setType($questionData['type'])
                ->setOptions($questionData['options'])
                ->setGameQuestion($game);
            $manager->persist($question);
        }

        // Enregistrement des données
        $manager->flush();
    }
}
