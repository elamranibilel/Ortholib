<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListeOrthophonisteController extends AbstractController
{
    #[Route('/liste/orthophoniste', name: 'app_liste_orthophoniste')]
    public function index(): Response
    {
        return $this->render('liste_orthophoniste/index.html.twig', [
            'controller_name' => 'ListeOrthophonisteController',
        ]);
    }
}
