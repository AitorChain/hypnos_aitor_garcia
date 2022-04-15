<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use App\Entity\Reservation;
use App\Entity\Suite;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GerantDashboardController extends AbstractDashboardController
{
    #[Route('/gerance', name: 'gerance')]
    public function index(): Response
    {

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
            ->setTitle('<img style="width: 100%" src="/assets/images/hypnos-logo.png">');
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retourner au site', 'fa fa-reply', 'app_homepage');
        yield MenuItem::linkToCrud('Hôtels', 'fas fa-hotel', Etablissement::class);
        yield MenuItem::subMenu('Suites', 'fas fa-bed')->setSubItems([
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Suite::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir liste', 'fas fa-eye', Suite::class)
                ->setAction(Crud::PAGE_INDEX)
        ]);
        yield MenuItem::linkToCrud('Reservations', 'fas fa-key', Reservation::class);
    }
}
