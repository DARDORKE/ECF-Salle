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
            ->setTitle('SwapAccess - Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Partenaires');
        yield MenuItem::subMenu('Actions')->setSubItems([
            MenuItem::linkToCrud('Afficher', 'fas fa-eye', Partner::class),
            MenuItem::linkToCrud('Créer', 'fas fa-plus', Partner::class)->setAction(Crud::PAGE_NEW)
        ]);

        yield MenuItem::section('Structures');
        yield MenuItem::subMenu('Actions')->setSubItems([
            MenuItem::linkToCrud('Afficher', 'fas fa-eye', Structure::class),
            MenuItem::linkToCrud('Créer', 'fas fa-plus', Structure::class)->setAction(Crud::PAGE_NEW)
        ]);
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', User::class);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('Modules');
        yield MenuItem::linkToCrud('Afficher', 'fas fa-eye', Module::class);
        yield MenuItem::linkToCrud('Créer', 'fas fa-plus', Module::class)->setAction(Crud::PAGE_NEW);
    }
}
