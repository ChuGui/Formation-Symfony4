<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(User $user)
    {

        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}
