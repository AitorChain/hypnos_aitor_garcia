<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Repository\SuiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    #[Route('/hotel/{slug}', name: 'app_hotel')]
    public function index(SuiteRepository $suiteRepository, Etablissement $etablissement): Response
    {
        $suites = $suiteRepository->findBy(
            ['etablissement' => $etablissement],
            ['titre' => 'ASC']
        );

        return $this->render('hotel/index.html.twig', [
            'etablissement' => $etablissement,
            'suites' => $suites,
        ]);
    }
}