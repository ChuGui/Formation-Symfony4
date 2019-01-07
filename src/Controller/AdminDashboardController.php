<?php

namespace App\Controller;

use App\Service\Stats;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_dashboard")
     * @param ObjectManager $manager
     * @return Response
     */
    public function index(ObjectManager $manager, Stats $statsService)
    {
        $stats          = $statsService->getStats();
        $bestAnnonces   = $statsService->getAnnoncesStats('DESC');
        $worstAnnonces  = $statsService->getAnnoncesStats('ASC');

        return $this->render('admin/dashboard/index.html.twig',[
            'stats'          => $stats,
            'worstAnnonces'  => $worstAnnonces,
            'bestAnnonces'   => $bestAnnonces
        ]);
    }
}
