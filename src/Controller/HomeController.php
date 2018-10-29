<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

Class HomeController {

    /**
     * @Route("/", name="homepage")
     */
    public function home(){
        return new Response("<h1>BOnjour à tous</h1>");
    }
}