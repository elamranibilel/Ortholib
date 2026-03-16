<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Commentaire;
use App\Entity\Orthophoniste;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commentaire')]
final class CommentaireController extends AbstractController
{
    private CommentaireRepository $commentaireRepository;
    public function __construct(CommentaireRepository $commentaireRepository)
    {
        $this->commentaireRepository = $commentaireRepository;
    }

    #[Route('/', name: 'app_commentaire')]
    public function index(): Response
    {
        $commentaires = $this->commentaireRepository->findAll();

        foreach ($commentaires as $commentaire) {
            $user = $commentaire->getAuteur();
            $destinataire = $commentaire->getDestinataire();

            if ($user instanceof Orthophoniste) {
                $commentaire->setRoleAuteur('Orthophoniste');
            } elseif ($user instanceof Patient) {
                $commentaire->setRoleAuteur('Patient');
            } else {
                $commentaire->setRoleAuteur('Autre');
            }

            if ($destinataire instanceof Orthophoniste) {
                $commentaire->setRoleDestinataire('Orthophoniste');
            } elseif ($destinataire instanceof Patient) {
                $commentaire->setRoleDestinataire('Patient');
            } else {
                $commentaire->setRoleDestinataire('Autre');
            }
        }

        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setAuteur($this->getUser());
            $manager->persist($commentaire);
            $manager->flush();

            $this->addFlash('success', 'Commentaire créé avec succès !');
            return $this->redirectToRoute('app_commentaire');
        }
        return $this->render('commentaire/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_show')]
    public function show(int $id): Response
    {
        $commentaire = $this->commentaireRepository->find($id);

        $user = $commentaire->getAuteur();
        $destinataire = $commentaire->getDestinataire();

        if ($user instanceof Orthophoniste) {
            $commentaire->setRoleAuteur('Orthophoniste');
        } elseif ($user instanceof Patient) {
            $commentaire->setRoleAuteur('Patient');
        } else {
            $commentaire->setRoleAuteur('Autre');
        }

        if ($destinataire instanceof Orthophoniste) {
            $commentaire->setRoleDestinataire('Orthophoniste');
        } elseif ($destinataire instanceof Patient) {
            $commentaire->setRoleDestinataire('Patient');
        } else {
            $commentaire->setRoleDestinataire('Autre');
        }

        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit')]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_commentaire');
        }
        return $this->render('commentaire/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $manager->remove($commentaire);
            $manager->flush();
        }
        return $this->redirectToRoute('app_commentaire');
    }
}
