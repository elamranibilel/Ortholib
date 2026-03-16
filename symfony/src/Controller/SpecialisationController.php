<?php

namespace App\Controller;

use App\Entity\Specialisation;
use App\Form\SpecialisationType;
use App\Repository\SpecialisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/specialisation')]
final class SpecialisationController extends AbstractController
{
    #[Route(name: 'app_specialisation_index', methods: ['GET'])]
    public function index(SpecialisationRepository $specialisationRepository): Response
    {
        return $this->render('specialisation/index.html.twig', [
            'specialisations' => $specialisationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_specialisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $specialisation = new Specialisation();
        $form = $this->createForm(SpecialisationType::class, $specialisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($specialisation);
            $entityManager->flush();

            return $this->redirectToRoute('app_specialisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('specialisation/new.html.twig', [
            'specialisation' => $specialisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_specialisation_show', methods: ['GET'])]
    public function show(Specialisation $specialisation): Response
    {
        return $this->render('specialisation/show.html.twig', [
            'specialisation' => $specialisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_specialisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Specialisation $specialisation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpecialisationType::class, $specialisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_specialisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('specialisation/edit.html.twig', [
            'specialisation' => $specialisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_specialisation_delete', methods: ['POST'])]
    public function delete(Request $request, Specialisation $specialisation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $specialisation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($specialisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_specialisation_index', [], Response::HTTP_SEE_OTHER);
    }
}
