<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/annonces/{slug}/book", name="booking_create")
     * @param Annonce $annonce
     * @IsGranted("ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function book(Annonce $annonce, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();

            $booking->setBooker($user)
                ->setAnnonce($annonce);

            $manager->persist($booking);

            $manager->flush();

            $this->redirectToRoute('booking_show' , ['id' => $booking->getId()]);

        }


        return $this->render('booking/book.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'une réservation
     *
     * @Route("/booking/{id}", name="booking_show")
     *
     * @param Booking $booking
     */
    public function show(Booking $booking){

    }
}
