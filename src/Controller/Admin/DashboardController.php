<?php

namespace App\Controller\Admin;

use App\Entity\Module;
use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getUser();

        if ($user->isEnabled() === false) {

            throw $this->createAccessDeniedException('Votre compte est désactivé, veuillez contacter un administrateur afin qu\'il active votre compte.');
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle(
                '<img src="img/logo.png" alt="logo" width="50" height="50"> SWAP ACCESS'
            )
            ->setFaviconPath('img/logo.png')

            ;
    }



    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Comptes des utilisateurs');
        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', User::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('Les Partenaires');
        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', Partner::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', Partner::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('Les Structures');

        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', Structure::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', Structure::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('Modules');
        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', Module::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', Module::class)->setAction(Crud::PAGE_NEW);
    }
}
