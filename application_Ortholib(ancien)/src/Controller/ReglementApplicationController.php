<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReglementApplicationController extends AbstractController
{
    #[Route('/reglement/application', name: 'app_reglement_application')]
    public function index(): Response
    {
        return $this->render('reglement_application/index.html.twig', [
            'controller_name' => 'ReglementApplicationController',
        ]);
    }
}
