<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Cabinet;
use App\Form\CabinetType;
use App\Entity\Orthophoniste;
use App\Repository\CabinetRepository;
use App\Repository\OrthophonisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CabinetController extends AbstractController
{
    private $passwordHasher;
    private $cabinetRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, CabinetRepository $cabinetRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->cabinetRepository = $cabinetRepository;
    }

    #[Route('/cabinet', name: 'app_cabinet')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $query = $this->cabinetRepository->findAll();
        $cabinets = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10,
        );
        return $this->render('cabinet/index.html.twig', [
            'cabinets' => $cabinets,
        ]);
    }

    #[Route('/cabinet/new', name: 'app_cabinet_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $cabinet = new Cabinet();
        $form = $this->createForm(CabinetType::class, $cabinet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('imagesCabinet')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);

                $img = new Image();
                $img->setName($fichier);
                $cabinet->addImagesCabinet($img);
            }
            $manager->persist($cabinet);
            $manager->flush();
            return $this->redirectToRoute('app_cabinet');
        }
        return $this->render('cabinet/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/cabinet/{id}', name: 'app_cabinet_show')]
    public function show(Cabinet $cabinet): Response
    {
        return $this->render('cabinet/show.html.twig', [
            'cabinet' => $cabinet,
            'orthophonistes' => $cabinet->getOrthophonistes(),
        ]);
    }

    #[Route('/cabinet/{id}/listes-ortho', name: 'app_cabinet_ortho')]
    public function listesOrtho(Cabinet $cabinet, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $cabinet->getOrthophonistes(), // Source de données
            $request->query->getInt('page', 1), // Numéro de page
            10 // Nombre d'éléments par page
        );
        return $this->render('cabinet/listesOrtho.html.twig', [
            'cabinet' => $cabinet,
            'orthophonistes' => $pagination,
        ]);
    }

    #[Route('/cabinet/{id}/orthophoniste', name: 'app_cabinet_orthophoniste')]
    public function orthophoniste($id, Request $request, PaginatorInterface $paginator, CabinetRepository $cabinetRepository, OrthophonisteRepository $orthophonisteRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Orthophoniste && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        $cabinet = $cabinetRepository->find($id);
        if (!$cabinet) {
            throw $this->createNotFoundException('Cabinet not found');
        }

        //$query = $cabinetRepository->getOrthophonistesByCabinet($cabinet);

        $query = $cabinet->getOrthophonistes();
        $orthophonistes = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10,
        );

        return $this->render('cabinet/listesOrtho.html.twig', [
            'orthophonistes' => $orthophonistes,
            'cabinet' => $cabinet,
        ]);
    }


    #[Route('/cabinet/nom', name: 'app_cabinet_nom_asc')]
    public function indexNom(Request $request, PaginatorInterface $paginator): Response
    {
        $query = $this->cabinetRepository->findNomASC();
        $cabinets = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10,
        );

        return $this->render('cabinet/index.html.twig', ['cabinets' => $cabinets]);
    }

    #[Route('/cabinet/{id}/edit', name: 'app_cabinet_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Cabinet $cabinet): Response
    {
        $form = $this->createForm(CabinetType::class, $cabinet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('imagesCabinet')->getData();
            if ($images) {
                foreach ($cabinet->getImagesCabinet() as $existingImage) {
                    $image = $this->getParameter('images_directory') . '/' . $existingImage->getName();
                    if (file_exists($image)) {
                        unlink($image);
                    }
                    $cabinet->removeImagesCabinet($existingImage);
                    $manager->remove($existingImage);
                }
                foreach ($images as $image) {
                    if ($image instanceof UploadedFile) {
                        $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                        $image->move(
                            $this->getParameter('images_directory'),
                            $fichier,
                        );
                        $img = new Image();
                        $img->setName($fichier);
                        $cabinet->addImagesCabinet($img);
                    }
                }
            }
            $manager->persist($cabinet);
            $manager->flush();
            return $this->redirectToRoute('app_cabinet');
        }
        return $this->render('cabinet/edit.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/cabinet/{id}/delete', name: 'app_cabinet_delete', methods: ['POST'])]
    public function delete(Request $request, Cabinet $cabinet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cabinet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cabinet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cabinet', [], Response::HTTP_SEE_OTHER);
    }
}
