<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces_index")
     */
    public function index(AnnonceRepository $repository)
    {
        $annonces = $repository->findAll();

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    /**
     * Permet de créer une annonce
     *
     * @Route("/annonces/new", name="annonces_create")
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            forEach ($annonce->getImages() as $image) {
                $image->setAnnonce($annonce);
                $manager->persist($image);
            }

            $annonce->setAuthor($this->getUser());

            $manager->persist($annonce);
            $manager->flush();


            $this->addFlash(
                'success',
                "Félicitation ! L'annonce <strong>{$annonce->getSlug()}test</strong> à bien été enregistrée !");

            return $this->redirectToRoute('annonces_show', [
                'slug' => $annonce->getSlug()
            ]);
        }


        return $this->render('annonce/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     *
     * @Route("/annonces/{slug}", name="annonces_show")
     * @return Response
     */
    public function show(Annonce $annonce)
    {
        //Je récupère l'annonce qui correspond au slug !

        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * Permet d'afficher un formulaire d'édition
     *
     * @Route("/annonces/{slug}/edit", name="annonces_edit")
     */
    public function edit(Annonce $annonce, Request $request, ObjectManager $manager)
    {

        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            forEach ($annonce->getImages() as $image) {
                $image->setAnnonce($annonce);
                $manager->persist($image);
            }

            $manager->persist($annonce);
            $manager->flush();


            $this->addFlash(
                'success',
                "Félicitation ! L'annonce <strong>{$annonce->getSlug()}test</strong> à bien été modifiée !");
            return $this->redirectToRoute('annonces_show', [
                'slug' => $annonce->getSlug()
            ]);
        }
        return $this->render('annonce/edit.html.twig', [
            'form' => $form->createView(),
            'annonce' => $annonce
        ]);
    }

}
