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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SwapAccess - Administration');
    }



    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Les Partenaires');
        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', Partner::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', Partner::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('Les Structures');

        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', Structure::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', Structure::class)->setAction(Crud::PAGE_NEW);


        yield MenuItem::section('Comptes des utilisateurs');
        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', User::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('Modules');
        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', Module::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', Module::class)->setAction(Crud::PAGE_NEW);
    }
}
