<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Entity\Signification;
use App\Form\SignificationType;
use App\Repository\ExerciceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SignificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/signification')]
final class SignificationController extends AbstractController
{
    #[Route(name: 'app_signification_index', methods: ['GET'])]
    public function index(SignificationRepository $significationRepository): Response
    {
        return $this->render('signification/index.html.twig', [
            'significations' => $significationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_signification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $signification = new Signification();
        $form = $this->createForm(SignificationType::class, $signification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($signification);
            $entityManager->flush();

            return $this->redirectToRoute('app_signification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('signification/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_signification_show', methods: ['GET'])]
    public function show(Signification $signification): Response
    {
        return $this->render('signification/show.html.twig', [
            'signification' => $signification,
        ]);
    }

    #[Route('/new/{id}', name: 'app_signification_new_id', methods: ['GET', 'POST'])]
    public function newID(Request $request, EntityManagerInterface $entityManager, ExerciceRepository $exerciceRepository, int $id): Response
    {
        $exercice = $exerciceRepository->find($id);

        if (!$exercice) {
            throw $this->createNotFoundException("L'exercice n'existe pas.");
        }

        $signification = new Signification();

        $signification->addExercice($exercice);

        $form = $this->createForm(SignificationType::class, $signification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($signification);
            $entityManager->flush();

            return $this->redirectToRoute('app_exercice');
        }

        return $this->render('signification/new.html.twig', [
            'signification' => $signification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_signification_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Signification $signification, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignificationType::class, $signification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_signification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('signification/edit.html.twig', [
            'signification' => $signification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_signification_delete', methods: ['POST'])]
    public function delete(Request $request, Signification $signification, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $signification->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($signification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_signification_index', [], Response::HTTP_SEE_OTHER);
    }
}
