<?php

namespace App\DataFixtures;

use App\Entity\Choice;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TestNiveau1Fixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $questionsData = [
            [
                'text' => 'What is the capital of France?',
                'level' => 1,
                'choices' => [
                    ['text' => 'Paris', 'isCorrect' => true],
                    ['text' => 'London', 'isCorrect' => false],
                    ['text' => 'Berlin', 'isCorrect' => false],
                ],
            ],
            [
                'text' => 'What is 2 + 2?',
                'level' => 1,
                'choices' => [
                    ['text' => '3', 'isCorrect' => false],
                    ['text' => '4', 'isCorrect' => true],
                    ['text' => '5', 'isCorrect' => false],
                ],
            ],
            // Level 2 questions
            [
                'text' => 'What is the capital of France?',
                'level' => 2,
                'choices' => [
                    ['text' => 'Paris', 'isCorrect' => true],
                    ['text' => 'London', 'isCorrect' => false],
                    ['text' => 'Berlin', 'isCorrect' => false],
                ],
            ],
            [
                'text' => 'What is 2 + 2?',
                'level' => 2,
                'choices' => [
                    ['text' => '3', 'isCorrect' => false],
                    ['text' => '4', 'isCorrect' => true],
                    ['text' => '5', 'isCorrect' => false],
                ],
            ],
            // Level 3 questions
            [
                'text' => 'What is the capital of France?',
                'level' => 3,
                'choices' => [
                    ['text' => 'Paris', 'isCorrect' => true],
                    ['text' => 'London', 'isCorrect' => false],
                    ['text' => 'Berlin', 'isCorrect' => false],
                ],
            ],
            [
                'text' => 'What is 2 + 2?',
                'level' => 3,
                'choices' => [
                    ['text' => '3', 'isCorrect' => false],
                    ['text' => '4', 'isCorrect' => true],
                    ['text' => '5', 'isCorrect' => false],
                ],
            ],
        ];

        foreach ($questionsData as $questionData) {
            $question = new Question();
            $question->setTexte($questionData['text']);
            $question->setLevel($questionData['level']);
            foreach ($questionData['choices'] as $choiceData) {
                $choice = new Choice();
                $choice->setText($choiceData['text']);
                $choice->setIsCorrect($choiceData['isCorrect']);
                $choice->setQuestion($question);
                $manager->persist($choice);
            }
            $manager->persist($question);
        }

        $manager->flush();
    }
}
