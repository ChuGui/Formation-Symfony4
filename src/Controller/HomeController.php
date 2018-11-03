<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

Class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        $prenoms = ["lior" => 31, "guillaume" => 32, "hall" => 43];
        return $this->render('home.html.twig', ['prenoms' => $prenoms, 'title' => 'Bonjour à tous', 'age' => 16]);
    }

}