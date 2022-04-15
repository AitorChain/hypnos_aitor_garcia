<?php

namespace App\Controller\Admin;

use App\Entity\Demande;
use App\Entity\Etablissement;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
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
            ->setTitle('<img style="width: 100%" src="/assets/images/hypnos-logo.png">')
            ->setFaviconPath('favicon.png');

    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retourner au site', 'fa fa-reply', 'app_homepage');

        yield MenuItem::subMenu('Hôtels', 'fas fa-hotel')->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Etablissement::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir liste', 'fas fa-eye', Etablissement::class)
                ->setAction(Crud::PAGE_INDEX)
        ]);

        yield MenuItem::subMenu('Gérants', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Utilisateur::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir liste', 'fas fa-eye', Utilisateur::class)
                ->setAction(Crud::PAGE_INDEX)
            ]);

        yield MenuItem::linkToCrud('Demandes', 'fas fa-envelope', Demande::class);

    }

}
