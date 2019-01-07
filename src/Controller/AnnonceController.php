<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="annonce_index")
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
     * @Route("/annonce/new", name="annonce_create")
     *
     * @IsGranted("ROLE_USER")
     *
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

            return $this->redirectToRoute('annonce_show', [
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
     * @Route("/annonce/{slug}", name="annonce_show")
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
     * @Route("/annonce/{slug}/edit", name="annonce_edit")
     * @Security("is_granted('ROLE_USER') and user === annonce.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
     *
     *
     * @param Annonce $annonce
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
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
            return $this->redirectToRoute('annonce_show', [
                'slug' => $annonce->getSlug()
            ]);
        }
        return $this->render('annonce/edit.html.twig', [
            'form' => $form->createView(),
            'annonce' => $annonce
        ]);
    }


    /**
     * Permet de supprimer une annonce
     *
     * @Route("/annonce/{slug}/delete" , name="annonce_delete")
     * @Security("is_granted('ROLE_USER') and user == annonce.getAuthor()", message="Vous n'avez pas le droit d'acceder à cette ressource")
     *
     *
     * @param Annonce $annonce
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function delete(Annonce $annonce, ObjectManager $manager){
        $manager->remove($annonce);
        $manager->flush();

        $this->addFlash("success", "L'annonce <strong>{$annonce->getTitle()}</strong> à bien été supprimée !");

        return $this->redirectToRoute("annonce_index");
    }

}
