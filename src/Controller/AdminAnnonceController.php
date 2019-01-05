<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Service\Paginator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAnnonceController extends AbstractController
{
    /**
     * @Route("/admin/annonce/{page<\d+>?1}", name="admin_annonce_index", requirements={"page": "\d+"})
     * @param int $page
     * @param Paginator $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($page, Paginator $paginator)
    {
        $paginator->setEntityClass(Annonce::class)
            ->setCurrentPage($page);

        return $this->render('admin/annonce/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/annonce/{id}/edit", name="admin_annonce_edit")
     *
     * @param Annonce $annonce
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Annonce $annonce, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($annonce);
            $manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$annonce->getTitle()}</strong> à bien été modifiée");
        }

        return $this->render('admin/annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/annonce/{id}/delete", name="admin_annonce_delete")
     * @param Annonce $annonce
     * @param ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Annonce $annonce, ObjectManager $manager)
    {

        if (count($annonce->getBookings()) > 0) {
            $this->addFlash('danger', "Vous ne pouvez pas supprimée l'annonce <strong>{$annonce->getTitle()}</strong> car elle possède déjà une ou plusieures réservations");

        } else {
            $manager->remove($annonce);
            $manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$annonce->getTitle()}</strong> à bien été supprimée !");
        }
        return $this->redirectToRoute('admin_annonce_index');
    }
}
