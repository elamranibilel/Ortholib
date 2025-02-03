<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NouveautesController extends AbstractController
{
    #[Route('/nouveautes', name: 'app_nouveautes')]
    public function index(): Response
    {
        return $this->render('nouveautes/index.html.twig', [
            'controller_name' => 'NouveautesController',
        ]);
    }
}
