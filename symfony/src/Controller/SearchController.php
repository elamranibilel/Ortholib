<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchType;
use App\Repository\UserRepository;
use App\Repository\PatientRepository;
use App\Repository\OrthophonisteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SearchController extends AbstractController
{
    private $patientRepo;
    private $orthophonisteRepo;
    private $userRepo;

    public function __construct(PatientRepository $patientRepo, OrthophonisteRepository $orthophonisteRepo, UserRepository $userRepo)
    {
        $this->patientRepo = $patientRepo;
        $this->orthophonisteRepo = $orthophonisteRepo;
        $this->userRepo = $userRepo;
    }

    #[Route('/search', name: 'app_search')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            // $patients = $this->patientRepo->searchPatients($query);
            //$orthophonistes = $this->orthophonisteRepo->searchOrthophonistes($query);
            $users = $this->userRepo->searchUser($query);
        }

        return $this->render('search/index.html.twig', [
            'patients' => $patients ?? [],
            'orthophonistes' => $orthophonistes ?? [],
            'users' => $users ?? [],
            'form' => $form->createView(),
        ]);
    }
}
