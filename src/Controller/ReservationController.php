<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation', name: 'app_reservation')]
class ReservationController extends AbstractController
{

    #[Route('/', name: '_new')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservation->setCreatedAt(new \DateTimeImmutable());
            $reservation->setClient($this->getUser());
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('reservation/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/check_availability', name:'_check_availability', methods: ['GET', 'POST'])]

    public function checkAvailability(ReservationRepository $reservationRepository, Request $request): Response
    {
        $suite = $request->request->get('suite');
        $checkIn = $request->request->get('checkIn');
        $checkOut = $request->request->get('checkOut');
        $checkInDate = strtotime($checkIn);
        $checkOutDate = strtotime($checkOut);
        $yesterday = time() - (86400);

        if (!empty($suite) && !empty($checkIn) && !empty($checkOut)) {

            if ($checkInDate >= $checkOutDate || $checkInDate <= $yesterday) {

                return $this->json([
                    'code' => 200,
                    'status' => 'error',
                    'message' => 'Date invalide'
                ], 200);

            } else {

                $reservations = $reservationRepository->isAvailable($suite, $checkIn, $checkOut);

                if (count($reservations) === 0) {

                    return $this->json([
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Date disponible'
                    ], 200);

                } else {

                    return $this->json([
                        'code' => 200,
                        'status' => 'error',
                        'message' => 'Date indisponible'
                    ], 200);

                }
            }
        }
        return $this->json([
            'code' => 200,
            'status' => 'error'
        ], 200);
    }
}
