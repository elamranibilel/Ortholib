<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/* #[Route('/admin')]
 */

class UserController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    // User Management
    #[Route('/user', name: 'app_user')]
    public function indexUsers(Request $request, PaginatorInterface $paginator, UserRepository $userRepo): Response
    {
        $query = $userRepo->findAll();
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10,
        );
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/new', name: 'app_user_new')]
    public function newUser(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/user/{id}', name: 'app_user_show')]
    public function showUser(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function editUser(Request $request, EntityManagerInterface $manager, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/edit.html.twig', ['formCreateUtilisateur' => $form->createView()]);
    }

    #[Route('user/trie/id', name: 'app_user_trie_id', methods: ['GET'])]
    public function trieUtilisateurID(Request $request, PaginatorInterface $paginator, UserRepository $utilisateurRepo): Response
    {
        $query = $utilisateurRepo->trieUtilisateurID();
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('utilisateur/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('user/trie/nom', name: 'app_user_trie_nom', methods: ['GET'])]
    public function trieUtilisateurNom(Request $request, PaginatorInterface $paginator, UserRepository $utilisateurRepo): Response
    {
        $query = $utilisateurRepo->trieUtilisateurNom();
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('utilisateur/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/delete/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function deleteUser(int $id, Request $request, EntityManagerInterface $manager, CsrfTokenManagerInterface $csrfManager, UserRepository $userRepo): Response
    {
        $token = $request->request->get('_token');

        if (!$csrfManager->isTokenValid(new CsrfToken('delete' . $id, $token))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $user = $userRepo->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('app_user');
    }
}
