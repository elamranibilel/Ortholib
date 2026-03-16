<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Patient;
use App\Form\PatientType;
use App\Entity\Orthophoniste;
use App\Form\OrthophonisteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/register')]
final class RegisterController extends AbstractController
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/patient', name: 'app_register_patient')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if ($user instanceof Patient) {
            return $this->redirectToRoute('app_patient');
        }

        $patient = new Patient();

        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setRoles(['ROLE_PATIENT']);
            $patient->setPassword($this->passwordHasher->hashPassword($patient, $patient->getPassword()));
            $images = $form->get('imagesPatient')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);

                $img = new Image();
                $img->setName($fichier);
                $patient->addImagesPatient($img);
            }
            $patient->setIsConfirmed(false);
            $manager->persist($patient);
            $manager->flush();
            if ($this->getUser() == null) {
                return $this->redirectToRoute('app_login');
            } else {
                return $this->redirectToRoute('app_patient');
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/orthophoniste', name: 'app_register_orthophoniste')]
    public function indexOrthophoniste(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if ($user instanceof Orthophoniste) {
            return $this->redirectToRoute('app_orthophoniste');
        }

        $orthophoniste = new Orthophoniste();
        $form = $this->createForm(OrthophonisteType::class, $orthophoniste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orthophoniste->setRoles(['ROLE_ORTHO']);
            $orthophoniste->setPassword($this->passwordHasher->hashPassword($orthophoniste, $orthophoniste->getPassword()));
            $images = $form->get('imagesOrthophoniste')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);

                $img = new Image();
                $img->setName($fichier);
                $orthophoniste->addImagesOrthophoniste($img);
            }
            $manager->persist($orthophoniste);
            $manager->flush();
            if ($this->getUser() == null) {
                return $this->redirectToRoute('app_login');
            } else {
                return $this->redirectToRoute('app_orthophoniste');
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
