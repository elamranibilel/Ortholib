<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Cabinet;
use App\Entity\Patient;
use App\Entity\Orthophoniste;
use App\Entity\Administrateur;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Controller\Admin\Crud\UserCrudController;
use App\Entity\Seances;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[AdminDashboard(routePath: '/admin/dashboard', routeName: 'admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        /* $user = $this->getUser(); */

        return parent::configureUserMenu($user)
            ->setName($user->getUserIdentifier())
            ->addMenuItems([
                MenuItem::linkToRoute('Mon compte', 'fa fa-solid fa-user', 'app_administrateur_show', [
                    'id' => $user->getId()
                ]),
            ]);
    }
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ortho')
            ->setFaviconPath('favicon.ico')
            ->setLocales(['fr' => 'Français']);
    }

    /* public function configureCrud(): Crud
    {
        return Crud::new()->setDateTimeFormat('d-M-Y H:i');
    } */

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToRoute('Retour', 'fa fa-solid fa-rotate-left', 'app_administrateur'),
            MenuItem::subMenu('Comptes', 'fa fa-solid fa-user')->setSubItems([
                MenuItem::linkToCrud('Utilisateur', '', User::class),
                MenuItem::linkToCrud('Administrateur', '', Administrateur::class),
                MenuItem::linkToCrud('Orthophoniste', '', Orthophoniste::class),
                MenuItem::linkToCrud('Patient', '', Patient::class),
            ]),
            MenuItem::subMenu('Cabinets', 'fa fa-solid fa-building')->setSubItems([
                MenuItem::linkToCrud('Cabinet', '', Cabinet::class),
                MenuItem::linkToCrud('Séances', '', Seances::class),
            ]),
        ];
    }
}
