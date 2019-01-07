<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

Class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function homepage(AnnonceRepository $annonceRepository, UserRepository $userRepository)
    {
        return $this->render('home.html.twig', [
            'annonces' => $annonceRepository->findBestAnnonces(3),
            'users' => $userRepository->findBestUsers()
        ]);
    }

}