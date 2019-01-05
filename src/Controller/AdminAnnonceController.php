<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAnnonceController extends AbstractController
{
    /**
     * @Route("/admin/annonces", name="admin_annonces_index")
     * @param AnnonceRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(AnnonceRepository $repo)
    {

        return $this->render('admin/annonce/index.html.twig', [
            'annonces' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/annonces/{id}/edit", name="admin_annonces_edit")
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
     * @Route("/admin/annonces/{id}/delete", name="admin_annonces_delete")
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
        return $this->redirectToRoute('admin_annonces_index');
    }
}
