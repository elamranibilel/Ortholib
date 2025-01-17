<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EspacePatientController extends AbstractController
{
    #[Route('/espace/patient', name: 'app_espace_patient')]
    public function index(): Response
    {
        return $this->render('espace_patient/index.html.twig', [
            'controller_name' => 'EspacePatientController',
        ]);
    }
}
