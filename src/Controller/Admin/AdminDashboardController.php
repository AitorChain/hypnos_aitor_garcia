<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use App\Entity\Gerant;
use App\Entity\Reservation;
use App\Entity\Suite;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\SubMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(EtablissementCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ecf Hotel');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::subMenu('Etablissements', 'fas fa-hotel')->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Etablissement::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir liste', 'fas fa-eye', Etablissement::class)
                ->setAction(Crud::PAGE_INDEX)
        ])
            ->setPermission('ROLE_ADMIN');

        yield MenuItem::subMenu('Gérants', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Utilisateur::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir liste', 'fas fa-eye', Utilisateur::class)
                ->setAction(Crud::PAGE_INDEX)
            ])
            ->setPermission('ROLE_ADMIN');


        yield MenuItem::linkToCrud('Suites', 'fas fa-list', Suite::class)
            ->setPermission('ROLE_GERANT');
    }
}
